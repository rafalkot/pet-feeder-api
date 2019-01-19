<?php

declare(strict_types=1);

namespace App\Ui\Rest\Controller;

use Swagger\Annotations as SWG;

class AuthController extends RestController
{
    /**
     * @SWG\Tag(name="Auth")
     * @SWG\Parameter(
     *     in="header",
     *     required=true,
     *     description="Current JWT",
     *     type="string",
     *     name="Authorization",
     *     @SWG\Schema(example="Bearer YOUR_TOKEN")
     * )
     * @SWG\Response(
     *     response=200,
     *     description="",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="success", type="bool", example=true)
     *     )
     * )
     * @SWG\Response(
     *     response=401,
     *     description="Expired JWT token"
     * )
     */
    public function validateTokenAction()
    {
        return $this->view(['success' => true]);
    }
}
