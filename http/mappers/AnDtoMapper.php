<?php


class AnDtoMapper {

    /* Apps */

    static function appToDto(AnApp $item): array {
        return $item->toArray();
    }

    static function appsToDto(array $items): array {
        return array_map(fn($item) => self::appToDto($item), $items);
    }

    static function dtoToApp(array $data): AnApp {
        return AnApp::fromArray($data);
    }


    /* Devices */

    static function deviceToDto(AnDevice $item): array {
        return $item->toArray();
    }

    static function devicesToDto(array $items): array {
        return array_map(fn($item) => self::deviceToDto($item), $items);
    }

    static function dtoToDevice(array $data): AnDevice {
        return AnDevice::fromArray($data);
    }


    /* Announce */

    static function announceToDto(AnAnnounce $item): array {
        return $item->toArray();
    }

    static function announcesToDto(array $items): array {
        return array_map(fn($item) => self::announceToDto($item), $items);
    }

    static function dtoToAnnounce(array $data): AnAnnounce {
        return AnAnnounce::fromArray($data);
    }

}