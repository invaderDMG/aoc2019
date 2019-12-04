<?php


class PasswordChecker
{

    public function getPossiblePasswords(string $input)
    {
        list($begin, $end) = explode("-", $input);
        $goodCandidates = 0;
        for($i = $begin; $i <= $end; $i++) {
            if ($this->checkCandidate($i)) {
                $goodCandidates++;
            }
        }
        return $goodCandidates;
    }

    private function checkCandidate($candidate)
    {
        $digits = str_split($candidate, 1);
        return $this->firstCondition($candidate) &&
            $this->secondCondition($candidate) &&
            $this->thirdCondition($digits) &&
            $this->fourthCondition($digits);
    }

    /**
     * @param $candidate
     * @return bool
     */
    private function firstCondition($candidate)
    {
        if ($candidate < 100000) {
            return false;
        }
        if ($candidate > 999999) {
            return false;
        }
        return true;
    }

    private function secondCondition($candidate)
    {
        return true;
    }

    private function thirdCondition($digits)
    {
        for($i = 0; $i < sizeof($digits)-1; $i++) {
            if ($digits[$i] == $digits[$i+1]) {
                return true;
            }
        }
        return false;
    }

    private function fourthCondition($digits)
    {
        for($i = 0; $i < sizeof($digits)-1; $i++) {
            if ($digits[$i] > $digits[$i+1]) {
                return false;
            }
        }
        return true;
    }

    public function getPossiblePasswordsWithExtraRules(string $input)
    {
        list($begin, $end) = explode("-", $input);
        $goodCandidates = 0;
        for($i = $begin; $i <= $end; $i++) {
            if ($this->checkCandidate($i) && $this->extraRules($i)) {
                $goodCandidates++;
            }
        }
        return $goodCandidates;
    }

    private function extraRules($candidate)
    {
        $digits = str_split($candidate, 1);
        return $this->fifthCondition($digits);
    }

    private function fifthCondition(array $digits)
    {
        $summary = [];
        foreach($digits as $digit) {
            if (!isset($summary[$digit])) {
                $summary[$digit] = 0;
            }
            $summary[$digit]++;
        }
        //remove repetitions > 2 and no repetitions at all
        foreach($summary as $digit => $repetitions) {
            if ($repetitions > 2 || $repetitions == 1) {
                unset($summary[$digit]);
            }
        }
        return sizeof($summary) > 0;
    }
}