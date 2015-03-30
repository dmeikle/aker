<?php

/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Gossamer\Aker\Components\Security\eventlisteners;

use Gossamer\Aker\Components\Core\FormToken;


/**
 * checks for tokens on form POST
 *
 * @author Dave Meikle
 */
class VerifyFormTokenListener extends BaseFormTokenListener{
    
    /**
     * 
     * @param Event $params
     */
    public function on_entry_point($params) {
        
        $token = $this->getToken();  
        if($token == false) {
            $this->eventDispatcher->dispatch('all', 'token_expired');
        }
        
        $this->checkTokenExists($token);   
        $defaultToken = $this->getDefaultToken();     
        
        $this->checkTokenValid($token, $defaultToken);
        $this->checkTokenDecayTime($token);
     
    }
    
    /**
     * checks to see if token exists. Notifies event dispatcher if not found
     * in case system is configured to handle this type of event.
     * 
     * @param type $token 
     */
    private function checkTokenExists($token) {
        if(is_null($token)) {
            $this->eventDispatcher->dispatch('all', 'token_missing');
        }
    }
    
    /**
     * checks a token to see if it is expired. Notifies event dispatcher if not found
     * in case system is configured to handle this type of event.
     * 
     * @param FormToken $token
     */
    private function checkTokenDecayTime(FormToken $token) {
        $currentTime = time();
        $tokenTime = $token->getTimestamp();
        if(($currentTime - $tokenTime) > __MAX_DECAY_TIME) {
            
            $this->eventDispatcher->dispatch('all', 'token_expired');
        }                
    }
    
    /**
     * checks to see if a token is valid. Notifies event dispatcher if not found
     * in case system is configured to handle this type of event.
     * 
     * @param FormToken $token     
     * @param FormToken $defaultToken
     */
    private function checkTokenValid(FormToken $token, FormToken $defaultToken) {
       
        if(!crypt($token->getTokenString(), $defaultToken->toString() == $defaultToken->toString())) {
          
            $this->eventDispatcher->dispatch('all', 'token_missing');
        }
    }
    
    /**
     * 
     * @return \Gossamer\Aker\Components\Core\FormToken
     */
    private function getToken() {
        $token = unserialize(getSession('_form_security_token'));
        
        return $token;
    }
}