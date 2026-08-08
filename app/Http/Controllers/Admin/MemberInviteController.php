<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberInvite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberInviteController extends Controller
{
    public function index(): View
    {
        $invites = MemberInvite::query()
            ->with('creator')
            ->withCount('uses')
            ->orderByDesc('id')
            ->paginate(30);

        $members = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'member'))
            ->orderByDesc('id')
            ->paginate(30, ['*'], 'members');

        return view('admin.invites.index', [
            'invites' => $invites,
            'members' => $members,
            'stats' => [
                'active_invites' => MemberInvite::query()
                    ->where('is_active', true)
                    ->where(fn ($query) => $query
                        ->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now()))
                    ->whereColumn('uses_count', '<', 'max_uses')
                    ->count(),
                'available_uses' => MemberInvite::query()
                    ->where('is_active', true)
                    ->where(fn ($query) => $query
                        ->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now()))
                    ->get(['max_uses', 'uses_count'])
                    ->sum(fn (MemberInvite $invite) => $invite->remainingUses()),
                'members' => $members->total(),
                'active_members' => User::query()
                    ->whereHas('roles', fn ($q) => $q->where('name', 'member'))
                    ->where('is_active', true)
                    ->count(),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'description' => ['nullable', 'string', 'max:160'],
            'max_uses' => ['required', 'integer', 'min:1', 'max:1000'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        $generated = MemberInvite::generateCode();

        MemberInvite::create([
            'code' => $generated['code'],
            'code_hash' => $generated['hash'],
            'code_prefix' => $generated['prefix'],
            'description' => $data['description'] ?? null,
            'max_uses' => (int) $data['max_uses'],
            'uses_count' => 0,
            'expires_at' => $data['expires_at'] ?? null,
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        return back()
            ->with('success', 'Convite criado. Código: '.$generated['code'])
            ->with('created_invite_code', $generated['code']);
    }

    public function toggle(MemberInvite $invite): RedirectResponse
    {
        $invite->update(['is_active' => ! $invite->is_active]);

        return back()->with('success', $invite->is_active ? 'Convite reativado.' : 'Convite desativado.');
    }

    public function destroy(MemberInvite $invite): RedirectResponse
    {
        if ($invite->uses_count > 0 || $invite->uses()->exists()) {
            $invite->update(['is_active' => false]);

            return back()->with(
                'error',
                'Este convite já foi utilizado e não pode ser excluído. Ele foi desativado para preservar o histórico.'
            );
        }

        $invite->delete();

        return back()->with('success', 'Convite removido.');
    }
}
