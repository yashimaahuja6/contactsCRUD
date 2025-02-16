<?php

namespace App\Http\Controllers;

use SimpleXMLElement;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'xml_file' => 'file|mimes:xml|max:2048', 
        // ]);
    
        // Handle XML import
        if ($request->hasFile('xml_file')) {
            $xmlFile = $request->file('xml_file');

            // Process XML data
            $contents = file_get_contents($xmlFile);
            $lines = explode("\n", $contents);

            foreach ($lines as $line) {
                // Trim whitespace and split name and phone number by '+'
                $parts = explode('+', trim($line));
    
                if (count($parts) >= 2) {
                    $name = trim($parts[0]);
                    $phone_number = '+' . trim($parts[1]);
    
                    // Store each contact from XML
                    Contact::create([
                        'name' => $name,
                        'phone_number' => $phone_number,
                    ]);
                }
            }
        } else{
            Contact::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
            ]);
        }
        return redirect()->route('contacts.index')->with('success', 'Contacts imported successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact = Contact::findOrFail($id);
        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contact = Contact::findOrFail($id);
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required'
        ]);
    
        $contact = Contact::findOrFail($id);
        $contact->update($request->all());
    
        return redirect()->route('contacts.index')
                         ->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('contacts.index')
                        ->with('success', 'Contact deleted successfully.');
    }
}
