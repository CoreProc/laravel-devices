<?php

namespace Coreproc\Devices\Http\Middleware;

use Closure;
use Illuminate\Validation\UnauthorizedException;

class StoreDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param null $guard
     * @param bool $isRequired
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null, $isRequired = true)
    {
        $deviceUdid = $request->header('X-Device-UDID');

        if (empty($deviceUdid) && ! $isRequired) {
            // We continue on
            return $next($request);
        }

        if (empty($deviceUdid) && $isRequired) {
            throw new UnauthorizedException('You need to specify your device details.');
        }

        // We save the device details
        $device = app(config('devices.device_model'))->query()->updateOrCreate([
            'udid' => $deviceUdid,
        ], [
            'os' => $request->header('X-Device-OS'),
            'os_version' => $request->header('X-Device-OS-Version'),
            'manufacturer' => $request->header('X-Device-Manufacturer'),
            'model' => $request->header('X-Device-Model'),
            'fcm_token' => $request->header('X-Device-FCM-Token'),
            'app_version' => $request->header('X-Device-App-Version'),
        ]);

        $request->device = $device;

        $request->guard = $guard ?? config('auth.defaults.guard');

        return $next($request);
    }

    public function terminate($request, $response)
    {
        $user = $request->user($request->guard);

        if (! empty($user) && ! empty($request->device)) {
            $request->device->deviceable()->associate($user);
            $request->device->save();
        }
    }
}
