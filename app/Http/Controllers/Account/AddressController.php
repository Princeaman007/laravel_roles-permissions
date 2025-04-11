namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'postal_code'   => 'required|string|max:20',
            'city'          => 'required|string|max:100',
            'state'         => 'required|string|max:100',
            'country'       => 'required|string|max:100',
            'phone'         => 'required|string|max:20',
            'type'          => 'required|in:shipping,billing,both',
            'is_default'    => 'nullable|boolean',
        ]);

        $validated['is_default'] = $request->has('is_default');

        $request->user()->addresses()->create($validated);

        return redirect()->back()->with('success', 'Adresse ajoutée avec succès.');
    }
}
