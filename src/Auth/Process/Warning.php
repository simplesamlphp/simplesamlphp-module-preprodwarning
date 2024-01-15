<?php

declare(strict_types=1);

namespace SimpleSAML\Module\preprodwarning\Auth\Process;

use SimpleSAML\Auth;
use SimpleSAML\Module;
use SimpleSAML\Utils;

/**
 * Give a warning that the user is accessing a test system, not a production system.
 *
 * @package SimpleSAMLphp
 */

class Warning extends Auth\ProcessingFilter
{
    /**
     * Process a authentication response.
     *
     * This function saves the state, and redirects the user to the page where the user
     * can authorize the release of the attributes.
     *
     * @param array $state  The state of the response.
     */
    public function process(array &$state): void
    {
        if (isset($state['isPassive']) && $state['isPassive'] === true) {
            // We have a passive request. Skip the warning
            return;
        }

        // Save state and redirect.
        $id = Auth\State::saveState($state, 'warning:request');
        $url = Module::getModuleURL('preprodwarning/warning');
        $httpUtils = new Utils\HTTP();
        $httpUtils->redirectTrustedURL($url, ['StateId' => $id]);
    }
}
