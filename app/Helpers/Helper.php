<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 24/10/19
 * Time: 07.25
 */

if (!function_exists('app_version')) {
    function app_version() {
        $MAJOR = 1;
        $MINOR = 1;
        $PATCH = 1;
        $commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));

        $commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
        $commitDate->setTimezone(new \DateTimeZone('UTC'));

        return sprintf(
            'v%s.%s.%s-dev.%s (%s)',
            $MAJOR, $MINOR, $PATCH, $commitHash,
            $commitDate->format('Y-m-d H:i:s')
        );
    }
}
