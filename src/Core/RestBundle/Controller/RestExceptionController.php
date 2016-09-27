<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 10-Jun-16
 * Time: 9:43 PM
 */

namespace Core\RestBundle\Controller;


use FOS\RestBundle\Controller\ExceptionController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class RestExceptionController extends ExceptionController
{
    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null)
    {
        return parent::showAction($request, $exception, $logger);
    }

}