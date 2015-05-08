<?php
/**
 * @author Jan Nehring <jan.nehring@dfki.de>
 */

namespace DFKI\ScorecardBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('name');
        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.new_password'),
            'second_options' => array('label' => 'form.new_password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',
        ));
        $builder->remove("current_password");
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'dfki_user_profile';
    }
}