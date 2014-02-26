<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.12.13
 * Time: 1:17
 */

namespace Ahonymous\Bundle\GuestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('email', 'email');
        $builder->add('message', 'textarea');
    }

    public function getDefaultOptions(/*array $options*/)
    {
        return array(
            'data_class' => 'Ahonymous\GuestBundle\Entity\Guest',
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'guest';
    }
}
