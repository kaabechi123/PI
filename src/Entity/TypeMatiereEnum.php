<?php

// src/Entity/TypeMatiereEnum.php
namespace App\Entity;

enum TypeMatiereEnum: string
{
    case PLASTIQUE = 'plastique';
    case VERRE = 'verre';
    case BOIS = 'bois';
    case TEXTILE = 'textile';
    case NON_DEFINI = 'undefined'; // Ajout d'un cas par défaut ou vide
}
