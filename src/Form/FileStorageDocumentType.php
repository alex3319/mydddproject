<?php

namespace App\Form;

use App\Entity\FileStorageDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FileStorageDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ...
            ->add('upload_file', FileType::class, [
                'label' => 'Upload File',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                //'required' => false,
                'required' => true,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        // mime Types: 
                        // https://www.iana.org/assignments/media-types/media-types.xhtml
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'text/csv',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid file',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Загрузить',
            ])
            // ...
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FileStorageDocument::class,
        ]);
    }
}