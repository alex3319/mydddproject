<?php

namespace App\Controller\Admin;

use App\Entity\SmsMessage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SmsMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SmsMessage::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
