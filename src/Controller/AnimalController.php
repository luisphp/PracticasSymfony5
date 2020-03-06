<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Animal;
use App\Repository\AnimalRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class AnimalController extends AbstractController
{
    /**
     * @Route("/animal", name="animal")
     */
    public function index()
    {

        $em = $this->getDoctrine()->getManager();

        //Encontrar todos registros
        
        $animal_repo = $this->getDoctrine()->getRepository(Animal::class);

        $animales = $animal_repo->findAll();

        //Query builder:
        // $qb = $animal_repo->createQueryBuilder('a')
        //                   ->andWhere("a.raza = 'boxer'")
        //                   ->getQuery();
        //                   $resultset = $qb->execute();

        //                   var_dump($resultset);
        //                   die();
        
        //DQL:
        //$dql = "SELECT a FROM App\Entity\Animal a WHERE a.raza = 'Boxer'";
        //$query = $em->createQuery($dql);                  
        //$resultset = $query->execute();

        //Pure SQL
        // $connection = $this->getDoctrine()->getConnection();
        // $sql = 'SELECT * FROM animales';
        // $prepare = $connection->prepare($sql);
        // $prepare->execute();
        // $resultset = $prepare->fetchAll();
        // var_dump($resultset);
        // die();

        $todos_animales = $animal_repo->findOneByRaza(['DESC']);
        var_dump($todos_animales);
        die();

        return $this->render('animal/index.html.twig', [
            'animales' => $animales,
        ]);
    }

    public function crear_animal(){

        //Formulario para crear animales
        $animal = new Animal();
        $form = $this->createFormBuilder()
                     ->setAction($this->generateUrl('animal_save'))
                     ->setMethod('POST')
                     ->add('tipo', textType::class)
                     ->add('raza', textType::class)
                     ->add('color', textType::class)
                     ->add('submit', textType::class)
                     ->getForm();



        return $this->render('animal/create.html.twig', ['form' => $form->createView()]);

    }

    public function update($id)
    {
        //Cargar Doctine
        $doctrine = $this->getDoctrine();
        //Cargar Entity manager
        $em = $doctrine->getManager();
        //Cargar Repo Animal
        $animal_repo = $em->getRepository(Animal::class);
        
        // Find para conseguir objeto
       $animal =  $animal_repo->find($id);

        //Comprobar si el objeto me llega
        if(!$animal){
            $message = "No se encontro el animal";
        }else{
        //Asignarle los valores al objeto
        $animal->setTipo('Roboperro');
        $animal->setColor('Metalico');
        $animal->setRaza('Roboperro');
        //Persistir en doctrine
        $em->persist($animal);
        //Gardar en la Db - Hacer FLUSH()
        $em->flush();

        $message = 'El animal actualizado tiene el ID/Tipo/raza: '.$animal->getId(). " ".$animal->getTipo(). " ".$animal->getRaza();
    }   
        //Respuesta
        return new Response($message);
    }    

    public function save(){

        //Guardar en una tabla de la base de datos
        
        $entity_manager = $this->getDoctrine()->getManager();

        $animal = new Animal();
        $animal->setTipo('Canino');
        $animal->setColor('Marron con manchas');
        $animal->setRaza('Boxer');
        
        //guardar objeto en doctrine que seria lo mismo que persistir el objeto

        $entity_manager->persist($animal);

        //Volcar datos en la tabla
         $entity_manager->flush();

        return new Response('El animal guardado tiene el ID'.$animal->getId());  
    }

    public function show($id){

        //Encontrar un registro en especifico

        $animal_repo = $this->getDoctrine()->getRepository(Animal::class);

        $animal = $animal_repo->find($id);

        //Mandarlos a la vista
        
        return $this->render('animal/show.html.twig', [
            'animal' => $animal,
        ]);
    }
    public function delete(Animal $animal){

        //Borrar un registro en especifico

        //Cargar Entity manager
        $em = $this->getDoctrine()->getManager();

        if($animal && is_object($animal)){

        //Aqui lo quita de la memoria que tiene doctrine
        $em->remove($animal);

        //Aqui se borra realmente el registro en la DB
        $em->flush();

        
            $message = "Animal borrado correctamente";
        }else{
          $message = "No se ha encontrado el animal";  
        }
             
        return new Response($message);
        
    }        
}
