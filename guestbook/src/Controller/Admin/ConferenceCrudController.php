<?php

namespace App\Controller\Admin;

use App\Entity\Conference;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use phpDocumentor\Reflection\Types\Boolean;

class ConferenceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Conference::class;
    }

   
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('city');
        
        yield TextField::new('year');
        
        yield TextField::new('slug')
        ->hideOnForm();

        yield BooleanField::new('international')
        ->onlyOnForms();

        
    }
    
}
