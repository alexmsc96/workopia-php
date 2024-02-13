<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

/**
 * Class ListingController
 * 
 * This class is responsible for handling listing-related operations.
 */
class ListingController
{
  protected $db;

  /**
   * ListingController constructor.
   * 
   * Initializes a new instance of the ListingController class.
   */
  public function __construct()
  {
    $config = require basePath('config/db.php');
    $this->db = new Database($config);
  }

  /**
   * Display a listing of listings.
   *
   * @return void
   */
  public function index()
  {
    $listings = $this->db->query('SELECT * FROM listings')->fetchAll();
    loadView('listings/index', ['listings' => $listings]);
  }

  /**
   * Show the form for creating a new listing.
   *
   * @return void
   */
  public function create()
  {
    loadView('listings/create');
  }

  /**
   * Display the specified listing.
   *
   * @return void
   */
  public function show($params)
  {
    $id = $params['id'] ?? '';
    $params = [
      'id' => $id
    ];

    $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    // Check if listing exists
    if (!$listing) {
      ErrorController::notFound('Listing not found');
      return;
    }

    loadView('listings/show', ['listing' => $listing]);
  }

  /**
   * Store data in database
   * 
   * @return void
   */

  public function store()
  {
    $allowedFields = ['title', 'description', 'salary', 'requirements', 'benefits', 'company', 'address', 'city', 'state', 'phone', 'email', 'tags'];

    $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

    $newListingData['user_id'] = 1;

    $newListingData = array_map('sanitize', $newListingData);

    $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

    $errors = [];

    foreach ($requiredFields as $field) {
      if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
        $errors[$field] = ucfirst($field) . ' is required';
      }
      ;
    }

    if (!empty($errors)) {
      // Reload view with errors 
      loadView('listings/create', [
        'errors' => $errors,
        'listing' => $newListingData
      ]);
    } else {
      // Submit data
      echo 'Data submitted successfully';
    }
  }

}