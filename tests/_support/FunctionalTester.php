<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests;

use Codeception\Actor;
use Codeception\Lib\Friend;
use Skyeng\MarketingCmsBundle\Tests\_generated\FunctionalTesterActions;

/**
 * Inherited Methods.
 *
 * @method void   wantToTest($text)
 * @method void   wantTo($text)
 * @method void   execute($callable)
 * @method void   expectTo($prediction)
 * @method void   expect($prediction)
 * @method void   amGoingTo($argumentation)
 * @method void   am($role)
 * @method void   lookForwardTo($achieveValue)
 * @method void   comment($description)
 * @method Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends Actor
{
    use FunctionalTesterActions;
}
