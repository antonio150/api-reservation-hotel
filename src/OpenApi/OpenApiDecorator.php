<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\OpenApi;
use ArrayObject;

class OpenApiDecorator implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        $path = '/users/{id}/file';
        $requestBody = new RequestBody(
            description: 'Upload a file for the user',
            content: new ArrayObject([
                'multipart/form-data' => [
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'file' => [
                                'type' => 'string',
                                'format' => 'binary',
                            ],
                        ],
                    ],
                ],
            ]),
            required: true
        );

        $operation = new Operation(
            operationId: 'postUserFile',
            tags: ['User'],
            summary: 'Upload a file for a user',
            requestBody: $requestBody,
            responses: [
                '200' => [
                    'description' => 'File uploaded successfully',
                ],
                '400' => [
                    'description' => 'Invalid input',
                ],
            ]
        );

        $pathItem = new PathItem(post: $operation);
        $openApi->getPaths()->addPath($path, $pathItem);

        return $openApi;
    }
}
