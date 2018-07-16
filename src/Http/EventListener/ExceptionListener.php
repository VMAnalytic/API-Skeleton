<?php

namespace App\Http\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface;

class ExceptionListener
{
    /**
     * @var string
     */
    private $env;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $route;

    /**
     * ExceptionListener constructor.
     * @param string $env
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @param RequestStack $requestStack
     */
    public function __construct(
        string $env,
        TranslatorInterface $translator,
        LoggerInterface $logger,
        RequestStack $requestStack)
    {
        $this->env = $env;
        $this->translator = $translator;
        $this->logger = $logger;
        $this->route = $requestStack->getMasterRequest()->attributes->get('_route');
    }

    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();
        // Customize your response object to display the exception details
        $response = new JsonResponse();
        $message = $this->translator->trans($exception->getMessage());

        if ($exception instanceof NotFoundHttpException) {
            $code = $exception->getCode() ?: Response::HTTP_NOT_FOUND;
            $response->setStatusCode($code);
            $message = $this->translator->trans('Object not found');
        } else {
            $code = $exception->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            $response->setStatusCode($code);
        }

        $data = [
            'code' => $response->getStatusCode(),
            'message' => $message,
        ];

        $response->setData($data);

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}
