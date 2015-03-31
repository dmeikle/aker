<?php

/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Gossamer\Aker\Components\Security\EventListeners;

use Gossamer\Aker\Components\Security\Core\FormToken;

/**
 * generates a token to be embedded in each form that will be posted to 
 * mitigate XSS attacks
 *
 * @author Dave Meikle
 */
class GenerateFormTokenListener extends BaseFormTokenListener {

    /**
     * generates the token and embeds it at the bottom of the form
     * 
     * @param Event $params
     */
    public function on_response_end(&$params) {

        $values = $params->getParams();

        $sessionToken = $this->getDefaultToken();
        $token = $sessionToken->generateTokenString();

        $tokenString = "<input type=\"hidden\" name=\"FORM_SECURITY_TOKEN\" value=\"$token\" />";

        $content = $values['content'];
        $values['content'] = str_replace('</form>', "$tokenString\r\n</form>", $content);
        $params->setParams($values);

        $this->storeFormToken($sessionToken);
    }

    /**
     * saves the token into session so we can check in on POST
     * 
     * @param FormToken $token
     */
    private function storeFormToken(FormToken $token) {
       
        setSession('_form_security_token', serialize($token));
    }

}
