<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Firestore;


class FirebaseConroller extends Controller
{
    protected $firestore;

    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }

    public function index()
    {
        // Use Firebase Firestore
        $collection = $this->firestore->collection('users');
        $documents = $collection->documents();

        // ... your code ...

        return view('example.index', ['documents' => $documents]);
    }
}
