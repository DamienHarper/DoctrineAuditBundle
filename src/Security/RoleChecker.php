<?php

namespace DH\AuditorBundle\Security;

use DH\Auditor\Provider\Doctrine\Configuration;
use DH\Auditor\Security\RoleCheckerInterface;
use DH\Auditor\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class RoleChecker implements RoleCheckerInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Security $security, Configuration $configuration)
    {
        $this->security = $security;
        $this->configuration = $configuration;
    }

    public function isGranted(string $entity, string $scope): bool
    {
        $userProvider = $this->configuration->getUserProvider();
        $user = null === $userProvider ? null : $userProvider->call($this);
        $security = null === $userProvider ? null : $this->security;

        if (!($user instanceof UserInterface) || !($security instanceof Security)) {
            // If no security defined or no user identified, consider access granted
            return true;
        }

        $entities = $this->configuration->getEntities();
        $roles = $entities[$entity]['roles'] ?? null;

        if (null === $roles) {
            // If no roles are configured, consider access granted
            return true;
        }

        if (!\array_key_exists($scope, $roles)) {
            // If no roles for the given scope are configured, consider access granted
            return true;
        }

        // roles are defined for the give scope
        foreach ($roles[$scope] as $role) {
            if ($security->isGranted($role)) {
                // role granted => access granted
                return true;
            }
        }

        // access denied
        return false;
    }
}
