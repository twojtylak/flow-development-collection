<?php
namespace TYPO3\Flow\Validation\Validator;

/*                                                                        *
 * This script belongs to the Flow framework.                             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the MIT license.                                          *
 *                                                                        */


/**
 * Validator to chain many validators in a disjunction (logical or).
 *
 * @api
 */
class DisjunctionValidator extends AbstractCompositeValidator
{
    /**
     * Checks if the given value is valid according to the validators of the
     * disjunction.
     *
     * So only one validator has to be valid, to make the whole disjunction valid.
     * Errors are only returned if all validators failed.
     *
     * @param mixed $value The value that should be validated
     * @return \TYPO3\Flow\Error\Result
     * @api
     */
    public function validate($value)
    {
        $validators = $this->getValidators();
        if ($validators->count() > 0) {
            $result = null;
            foreach ($validators as $validator) {
                $validatorResult = $validator->validate($value);
                if ($validatorResult->hasErrors()) {
                    if ($result === null) {
                        $result = $validatorResult;
                    } else {
                        $result->merge($validatorResult);
                    }
                } else {
                    if ($result === null) {
                        $result = $validatorResult;
                    } else {
                        $result->clear();
                    }
                    break;
                }
            }
        } else {
            $result = new \TYPO3\Flow\Error\Result();
        }

        return $result;
    }
}
