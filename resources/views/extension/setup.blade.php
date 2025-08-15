@extends('layouts.app') {{-- oder dein Layout --}}

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-xl font-semibold mb-4">Extension – Einrichtung</h1>

        <div class="p-4 rounded-xl border mb-4">
            <p class="text-sm text-gray-600">
                Dieser Link ist <strong>einmalig</strong> und wurde soeben verwendet.
                Falls du die Seite neu lädst, ist der Link ungültig.
            </p>
        </div>

        <div class="space-y-3 text-sm">
            <div>
                <div class="font-medium">Dein API-Endpunkt</div>
                <code class="block mt-1">{{ $apiBase }}/api/slot/current</code>
            </div>

            <div>
                <div class="font-medium">HTTP Header</div>
                <code class="block mt-1">Authorization: Bearer &lt;DEIN_TOKEN_AUS_DER_APP&gt;</code>
            </div>

            <div>
                <div class="font-medium">Hinweis</div>
                <p class="mt-1 text-gray-600">
                    Das <em>Plain Token</em> wird ausschließlich beim Erzeugen in der App angezeigt.
                    Falls verloren, erzeugst du bitte ein neues Token.
                </p>
            </div>

            <div class="text-xs text-gray-500">
                Token-ID: {{ $tokenId }} · Link gültig bis: {{ optional($expires)->format('d.m.Y H:i') }}
            </div>
        </div>
    </div>
@endsection
<?php
