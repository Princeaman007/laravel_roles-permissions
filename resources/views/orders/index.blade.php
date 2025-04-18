@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Liste des commandes</h1>
        
        <div class="d-flex">
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                <i class="bi bi-arrow-clockwise"></i> Actualiser
            </a>
            @if(auth()->user()->can('export-orders'))
            <a href="#" class="btn btn-sm btn-success">
                <i class="bi bi-file-earmark-excel"></i> Exporter
            </a>
            @endif
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow border-0 rounded-3 p-4 mb-4 bg-white">
        <h5 class="card-title mb-4 text-primary">Filtrer les commandes</h5>
        <form method="GET" action="{{ route('orders.index') }}" class="needs-validation" novalidate>
            <div class="row g-3 align-items-end">
                <!-- Numéro de commande -->
                <div class="col-lg-3 col-md-6">
                    <label for="order_number" class="form-label small text-secondary fw-semibold">
                        <i class="bi bi-search me-1"></i> N° Commande
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">🔎</span>
                        <input type="text" name="order_number" id="order_number" 
                               class="form-control border-start-0 bg-light" 
                               placeholder="Ex: CMD12345" value="{{ request('order_number') }}">
                    </div>
                </div>

                <!-- Statut -->
                <div class="col-lg-3 col-md-6">
                    <label for="status" class="form-label small text-secondary fw-semibold">
                        <i class="bi bi-box-seam me-1"></i> Statut
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">📦</span>
                        <select name="status" id="status" class="form-select border-start-0 bg-light">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>🕒 En attente</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>🔄 En traitement</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Complétée</option>
                            <option value="declined" {{ request('status') == 'declined' ? 'selected' : '' }}>❌ Annulée</option>
                        </select>
                    </div>
                </div>

                <!-- Date de début -->
                <div class="col-lg-2 col-md-6">
                    <label for="date_from" class="form-label small text-secondary fw-semibold">
                        <i class="bi bi-calendar-event me-1"></i> De
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">📅</span>
                        <input type="date" name="date_from" id="date_from" 
                               class="form-control border-start-0 bg-light"
                               value="{{ request('date_from') }}">
                    </div>
                </div>

                <!-- Date de fin -->
                <div class="col-lg-2 col-md-6">
                    <label for="date_to" class="form-label small text-secondary fw-semibold">
                        <i class="bi bi-calendar-event me-1"></i> À
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">📅</span>
                        <input type="date" name="date_to" id="date_to" 
                               class="form-control border-start-0 bg-light"
                               value="{{ request('date_to') }}">
                    </div>
                </div>

                <!-- Boutons -->
                <div class="col-lg-2 col-md-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center">
                            <i class="bi bi-funnel-fill me-2"></i> Filtrer
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary flex-grow-1 d-flex align-items-center justify-content-center" 
                           data-bs-toggle="tooltip" title="Réinitialiser les filtres">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tri caché -->
            <input type="hidden" name="sort_field" value="{{ request('sort_field', 'created_at') }}">
            <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">
        </form>
    </div>

    <!-- Résultats -->
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ $orders->total() }} commande(s) trouvée(s)
            </h6>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-list"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-grid"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            @if($orders->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">
                                    <a href="{{ route('orders.index', array_merge(request()->query(), ['sort_field' => 'id', 'sort_direction' => request('sort_field') === 'id' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                        #
                                        @if(request('sort_field') === 'id')
                                            <i class="bi bi-arrow-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('orders.index', array_merge(request()->query(), ['sort_field' => 'order_number', 'sort_direction' => request('sort_field') === 'order_number' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                        N° commande
                                        @if(request('sort_field') === 'order_number')
                                            <i class="bi bi-arrow-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Client</th>
                                <th>
                                    <a href="{{ route('orders.index', array_merge(request()->query(), ['sort_field' => 'created_at', 'sort_direction' => request('sort_field') === 'created_at' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                        Date
                                        @if(request('sort_field') === 'created_at' || !request('sort_field'))
                                            <i class="bi bi-arrow-{{ request('sort_direction', 'desc') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('orders.index', array_merge(request()->query(), ['sort_field' => 'status', 'sort_direction' => request('sort_field') === 'status' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                        Statut
                                        @if(request('sort_field') === 'status')
                                            <i class="bi bi-arrow-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Produits</th>
                                <th class="text-end">
                                    <a href="{{ route('orders.index', array_merge(request()->query(), ['sort_field' => 'total_amount', 'sort_direction' => request('sort_field') === 'total_amount' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                        Montant
                                        @if(request('sort_field') === 'total_amount')
                                            <i class="bi bi-arrow-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-center pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="ps-3 fw-bold">{{ $order->id }}</td>
                                    <td>
                                        <span class="text-primary font-monospace">
                                            {{ $order->order_number }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($order->user)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2 bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                                    <span class="text-white">{{ substr($order->user->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <div>{{ $order->user->name }}</div>
                                                    <div class="small text-muted">{{ $order->user->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $order->formattedDate }}</div>
                                        <div class="small text-muted">{{ $order->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td>
                                        @if($order->status === 'pending')
                                            <span class="badge bg-warning text-dark">🕒 {{ $order->formattedStatus }}</span>
                                        @elseif($order->status === 'processing')
                                            <span class="badge bg-info text-dark">🔄 {{ $order->formattedStatus }}</span>
                                        @elseif($order->status === 'completed')
                                            <span class="badge bg-success">✅ {{ $order->formattedStatus }}</span>
                                        @elseif($order->status === 'declined')
                                            <span class="badge bg-danger">❌ {{ $order->formattedStatus }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $order->formattedStatus }}</span>
                                        @endif
                                        
                                        <div class="small mt-1">
                                            <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : ($order->payment_status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                {{ $order->formattedPaymentStatus }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $order->totalItems }} article(s)
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold">
                                        {{ number_format($order->total_amount, 2, ',', ' ') }} €
                                    </td>
                                    <td class="text-center pe-3">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('orders.show', $order) }}">
                                                        <i class="bi bi-eye text-primary me-2"></i> Détails
                                                    </a>
                                                </li>
                                                @if(auth()->user()->can('process-order'))
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#updateStatusModal-{{ $order->id }}">
                                                        <i class="bi bi-arrow-repeat text-info me-2"></i> Changer statut
                                                    </a>
                                                </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('orders.show', $order) }}?invoice=true">
                                                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Facture
                                                    </a>
                                                </li>
                                                @if(auth()->user()->can('cancel-order'))
                                                @if(!$order->isCompleted && !$order->isDeclined)
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button class="dropdown-item text-danger" type="button" data-bs-toggle="modal" data-bs-target="#cancelOrderModal-{{ $order->id }}">
                                                        <i class="bi bi-x-circle me-2"></i> Annuler
                                                    </button>
                                                </li>
                                                @endif
                                                @endif
                                            </ul>
                                        </div>
                                        
                                        <!-- Modal pour changer le statut -->
                                        @if(auth()->user()->can('process-order'))
                                        <div class="modal fade" id="updateStatusModal-{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Modifier le statut</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Commande: <strong>{{ $order->order_number }}</strong></p>
                                                            <div class="mb-3">
                                                                <label for="status-{{ $order->id }}" class="form-label">Nouveau statut</label>
                                                                <select class="form-select" id="status-{{ $order->id }}" name="status" required>
                                                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>En traitement</option>
                                                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Terminée</option>
                                                                    <option value="declined" {{ $order->status === 'declined' ? 'selected' : '' }}>Annulée</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <!-- Modal pour annuler la commande -->
                                        @if(auth()->user()->can('cancel-order'))
                                        @if(!$order->isCompleted && !$order->isDeclined)
                                        <div class="modal fade" id="cancelOrderModal-{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmer l'annulation</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Êtes-vous sûr de vouloir annuler la commande <strong>{{ $order->order_number }}</strong> ?</p>
                                                        <p class="text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Cette action est irréversible.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                        <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center p-3">
                    <div class="text-muted small">
                        Affichage de {{ $orders->firstItem() ?? 0 }} à {{ $orders->lastItem() ?? 0 }} sur {{ $orders->total() }} commandes
                    </div>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted">Aucune commande trouvée</h5>
                    <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- CSS supplémentaire -->
<style>
    .avatar-sm {
        width: 30px;
        height: 30px;
    }
    
    .table th {
        font-weight: 600;
        white-space: nowrap;
    }
    
    .pagination {
        margin-bottom: 0;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
    }
</style>
@endsection