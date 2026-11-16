<?php

/*
 *  Copyright (C) BadPixxel <www.badpixxel.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace BadPixxel\Widgets\Demo\Services;

/**
 * Static Data of a Fictional Online Shop, for the Demo Dashboard
 *
 * Values are fixed, so the dashboard looks the same on every page load.
 */
class ShopDemoData
{
    /**
     * Months Labels
     */
    const MONTHS = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

    /**
     * Week Days Labels
     */
    const DAYS = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");

    /**
     * Get Monthly Revenue & Costs, in k€
     *
     * @return array<int, array{month: string, revenue: int, costs: int}>
     */
    public function getMonthlySales(): array
    {
        $revenue = array(32, 35, 41, 38, 45, 52, 49, 44, 56, 61, 72, 84);
        $costs = array(24, 25, 28, 27, 30, 33, 32, 30, 35, 37, 42, 47);
        $rows = array();
        foreach (self::MONTHS as $index => $month) {
            $rows[] = array("month" => $month, "revenue" => $revenue[$index], "costs" => $costs[$index]);
        }

        return $rows;
    }

    /**
     * Get Sales Share by Channel, in %
     *
     * @return array<int, array{channel: string, share: int}>
     */
    public function getSalesByChannel(): array
    {
        return array(
            array("channel" => "Website", "share" => 46),
            array("channel" => "Marketplaces", "share" => 27),
            array("channel" => "Mobile App", "share" => 16),
            array("channel" => "Retail Stores", "share" => 11),
        );
    }

    /**
     * Get Orders by Week Day, Online & In Store
     *
     * @return array<int, array{day: string, online: int, store: int}>
     */
    public function getOrdersByDay(): array
    {
        $online = array(182, 174, 196, 205, 238, 264, 221);
        $store = array(64, 58, 61, 72, 95, 142, 38);
        $rows = array();
        foreach (self::DAYS as $index => $day) {
            $rows[] = array("day" => $day, "online" => $online[$index], "store" => $store[$index]);
        }

        return $rows;
    }

    /**
     * Get Top Products: name, units sold, revenue & stock
     *
     * @return array<int, array{string, int, string, string}>
     */
    public function getTopProducts(): array
    {
        return array(
            array("Wireless Headphones", 412, "24 720 €", "In stock"),
            array("Smart Watch", 268, "21 440 €", "In stock"),
            array("Bluetooth Speaker", 245, "9 800 €", "Low stock"),
            array("USB-C Charger", 601, "7 210 €", "In stock"),
            array("Laptop Sleeve", 188, "4 700 €", "Out of stock"),
        );
    }

    /**
     * Get Hourly Visits Activity, by Week Day & Hour, for a Heatmap
     *
     * @return array<int, array{value: array{x: string, y: string, d: string, v: int}}>
     */
    public function getActivity(): array
    {
        $values = array();
        foreach (self::DAYS as $dayIndex => $day) {
            foreach (range(8, 22, 2) as $hour) {
                //==============================================================================
                // Busy evenings & weekends, quiet mornings: a smooth & stable pattern, from 0 to 100
                $level = 100 - 7 * abs($hour - 20) - ($dayIndex >= 5 ? 0 : 15) + (($dayIndex * 7 + $hour) % 9);
                $level = max(0, min(100, $level));
                $values[] = array("value" => array(
                    "x" => sprintf("%02dh", $hour),
                    "y" => $day,
                    "d" => sprintf("%s %02dh: %d%%", $day, $hour, $level),
                    //==============================================================================
                    // Matrix controller shades cells from 5 (transparent) to 45 (opaque)
                    "v" => (int) round(5 + 0.4 * $level),
                ));
            }
        }

        return $values;
    }

    /**
     * Get Activity Hours Labels
     *
     * @return string[]
     */
    public function getActivityHours(): array
    {
        return array_map(static fn (int $hour) => sprintf("%02dh", $hour), range(8, 22, 2));
    }
}
