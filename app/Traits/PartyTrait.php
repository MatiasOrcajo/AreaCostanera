<?php

namespace App\Traits;
use Carbon\Carbon;

trait PartyTrait
{

    /**
     * Get the color reference for difference between now and event date
     * @return string
     *
     */
    public function getCssForPartyBox()
    {

        $graduationDate = Carbon::parse($this->fecha);
        $now = Carbon::now();
        $diff = $graduationDate->diffInDays($now) + 1;

        switch ($diff) {

            case $diff <= 14:
                $css = "background: rgb(219,37,24);
                        background: linear-gradient(180deg, rgba(219,37,24,1.2) 35%, rgba(182,2,2,1.2) 70%);";
                break;
            case $diff > 14 && $diff < 30:
                $css = "background: rgb(239,237,13);
                        background: linear-gradient(180deg, rgba(239,237,13,1.2) 35%, rgba(210,201,20,1.2) 70%);";
                break;
            default:
                $css = "background: rgb(21,196,35);
                        background: linear-gradient(180deg, rgba(21,196,35,1.2) 35%, rgba(15,140,17,1.2) 70%);";
                break;

        }

        return $css;
    }

}
