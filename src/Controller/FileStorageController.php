<?php

namespace App\Controller;


use App\Entity\FileStorageImage;
use App\Entity\FileStorageDocument;
use App\Form\FileStorageImageType;
use App\Form\FileStorageDocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\FileUploader;
use App\Service\FileImageUploader;
use App\Service\FileDocumentUploader;


#[Route('/filestorage')]
class FileStorageController extends AbstractController
{

    #[Route('/', name: 'app_filestorage_index')]
    public function index()
    {
    }


    #[Route('/success', name: 'app_filestorage_success')]
    public function success()
    {
        return $this->render('filestorage/success.html.twig', []);
    }

    #[Route('/upload/image', name: 'app_filestorage_upload_image')]
    public function uploadimage(
        Request $request,
        SluggerInterface $slugger,
        ManagerRegistry $doctrine,
        FileImageUploader $fileUploader
    ) {
        $fileStorage = new FileStorageImage();
        $uuid = $fileStorage->uuid;

        $form = $this->createForm(FileStorageImageType::class, $fileStorage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadFile = $form->get('upload_file')->getData();
            if ($uploadFile) {
                $filename = $fileUploader->upload($uploadFile);
                $fileStorage->setFilename($filename);
                $entityManager = $doctrine->getManager();
                $entityManager->persist($fileStorage);
                $entityManager->flush();
                return $this->redirectToRoute('app_filestorage_success');
                //return $this->render('filestorage/success.html.twig', [
                //    'uuid' => $uuid,
                //]);  
            }
        }

        return $this->renderForm('filestorage/upload.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/upload/document', name: 'app_filestorage_upload_document')]
    public function uploaddocument(
        Request $request,
        SluggerInterface $slugger,
        ManagerRegistry $doctrine,
        FileDocumentUploader $fileUploader
    ) {
        $fileStorage = new FileStorageDocument();
        $uuid = $fileStorage->uuid;

        $form = $this->createForm(FileStorageDocumentType::class, $fileStorage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadFile = $form->get('upload_file')->getData();
            if ($uploadFile) {
                $filename = $fileUploader->upload($uploadFile);
                $fileStorage->setFilename($filename);
                $entityManager = $doctrine->getManager();
                $entityManager->persist($fileStorage);
                $entityManager->flush();
                return $this->redirectToRoute('app_filestorage_success');
                //return $this->render('filestorage/success.html.twig', [
                //    'uuid' => $uuid,
                //]);    
            }
        }

        return $this->renderForm('filestorage/upload.html.twig', [
            'form' => $form,
        ]);
    }
}
