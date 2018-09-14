<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 09/09/18
 * Time: 12:08
 */

namespace MegatronicApiBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MegatronicApiBundle\Document\MegatronicContext;
use MegatronicApiBundle\Document\MegatronicResource;
use MegatronicApiBundle\Document\MegatronicUser;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Fixtures implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface $container
     */
    protected $container;
    /**
     * @param ContainerInterface|null $container
     * @return mixed
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     * @return mixed
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUser();
        $this->loadResources($manager);
    }
    public function loadUser()
    {
        $userManager = $this->container->get('fos_user.user_manager');

        // Create our user and set details
        $user = $userManager->createUser();
        $user->setUsername('admin');
        $user->setEmail('email@domain.com');
        $user->setPlainPassword('admin');
        //$user->setPassword('3NCRYPT3D-V3R51ON');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));

        // Update the user
        $userManager->updateUser($user, true);
    }
    protected function loadResources(ObjectManager $manager)
    {
        $context1 = new MegatronicContext();
        $this->loadContext($manager, $context1);

        $resouce1 = new MegatronicResource();
        $resouce1
            ->setType('application/text')
            ->setDescription('test File 1')
            ->setExtension('txt')
            ->setMeta('raw')
            ->setApplications(['app1','app2'])
            ->setContext($context1);
        $manager->persist($resouce1);
        $manager->flush();
    }

    protected function loadContext(ObjectManager $manager, MegatronicContext $context1)
    {

        $context1->setName('download');
        $manager->persist($context1);
        $manager->flush();
    }
}
