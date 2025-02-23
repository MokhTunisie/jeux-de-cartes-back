<?php

namespace App\Infrastructure\EventListener;

use App\Domain\Exception\DomainException;
use App\Domain\Exception\InvalidCardAttributeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

readonly class ExceptionListener
{
    public function __construct(
        private SerializerInterface $serializer
    )
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $message = sprintf('Exception : %s', $exception->getMessage());
        $response = new Response();

        switch (true) {
            case $exception instanceof HttpExceptionInterface:
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
                $response->setContent($message);
                break;
            case $exception instanceof DomainException:
                $response = $this->setDomainResponse($response, $exception);
                break;
            default:
                $response->setContent($message);
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }

    private function setDomainResponse(Response $response, DomainException $exception): Response
    {
        $response->setStatusCode($exception->getCode());

        $reflect = new \ReflectionClass($exception);

        $content = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'title' => 'Domain Exception :: ' . $reflect->getShortName(),
        ];

        $serializedData = $this->serializer->serialize($content, JsonEncoder::FORMAT);

        return $response->setContent($serializedData);
    }
}
