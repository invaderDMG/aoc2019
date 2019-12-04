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
}