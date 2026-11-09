@extends('layouts.admin')

@section('title', 'Lead Acquisition & Outreach Pipeline')

@section('content')
@php
    $viewMode = $viewMode ?? 'table';
@endphp
<div class="space-y-6">
    <!-- Top Header Bar matching Image 4 -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-600 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/20">
                <i data-lucide="flame" class="w-5 h-5"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Lead Acquisition & Outreach Pipeline</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/20 uppercase tracking-wider">
                        LIVE SYNC
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-0.5">Multi-channel outreach tracking across WhatsApp, Instagram & Email</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- View Mode Switcher -->
            <div class="inline-flex rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 p-1 shadow-sm">
                <a href="{{ request()->fullUrlWithQuery(['view' => 'kanban']) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg flex items-center gap-1.5 transition-colors {{ $viewMode === 'kanban' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900' }}">
                    <i data-lucide="kanban" class="w-3.5 h-3.5"></i> Kanban
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'table']) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg flex items-center gap-1.5 transition-colors {{ $viewMode === 'table' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900' }}">
                    <i data-lucide="table" class="w-3.5 h-3.5"></i> Table
                </a>
            </div>

            <button onclick="openLeadModal()" type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm shadow-indigo-500/20">
                <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i> + Add Lead
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-xs font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20 flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-xs font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20">
            <div class="flex items-center gap-2 font-bold text-xs mb-1">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 ml-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 4 Top KPI Cards matching Image 4 -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Leads -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center">
                <i data-lucide="building-2" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total Leads</span>
                <div class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $totalLeadsCount }}</div>
            </div>
        </div>

        <!-- Card 2: Contacted -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <i data-lucide="send" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Contacted</span>
                <div class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $contactedCount }}</div>
            </div>
        </div>

        <!-- Card 3: Interested -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                <i data-lucide="sparkles" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">Interested</span>
                <div class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $interestedCount }}</div>
            </div>
        </div>

        <!-- Card 4: Won Deals -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Won Deals</span>
                    <div class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $wonCount }}</div>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/20">
                {{ $wonRevenueBadge }}
            </span>
        </div>
    </div>

    <!-- Filter Bar matching Image 4 -->
    <div class="bg-white dark:bg-gray-800 p-3.5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <form method="GET" action="{{ route('admin.crm.leads.index') }}" class="flex flex-wrap items-center gap-3 text-xs">
            <input type="hidden" name="view" value="{{ $viewMode }}">

            <div class="flex-1 min-w-[200px] relative">
                <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-2.5 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search business, problem, or site..." class="w-full pl-9 pr-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-xs">
            </div>

            <div class="flex items-center gap-1.5 text-gray-400">
                <span>Market:</span>
                <select name="market" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <option value="">All</option>
                    @foreach($markets as $m)
                        <option value="{{ $m }}" {{ $market === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-1.5 text-gray-400">
                <span>Service:</span>
                <select name="service" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <option value="">All</option>
                    @foreach($services as $s)
                        <option value="{{ $s }}" {{ $service === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-1.5 text-gray-400">
                <span>Channel:</span>
                <select name="channel" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <option value="">All</option>
                    @foreach($channels as $c)
                        <option value="{{ $c }}" {{ $channel === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            @if($search || $market || $service || $channel)
                <a href="{{ route('admin.crm.leads.index', ['view' => $viewMode]) }}" class="text-xs text-red-500 hover:underline py-1.5 px-2">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Quick Add Row matching Image 4 -->
    <div class="bg-white dark:bg-gray-800 p-3.5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <form action="{{ route('admin.crm.leads.store') }}" method="POST" class="flex flex-wrap items-center gap-2 text-xs">
            @csrf
            <div class="flex items-center gap-1.5 text-indigo-600 dark:text-indigo-400 font-bold whitespace-nowrap">
                <i data-lucide="zap" class="w-4 h-4"></i>
                <span>Quick Add:</span>
            </div>

            <input type="text" name="business_name" required placeholder="Business Name *" class="flex-1 min-w-[140px] px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">

            <select name="market" class="px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="India">India</option>
                <option value="Dubai">Dubai</option>
                <option value="Other">Other</option>
            </select>

            <select name="service" class="px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="Website">Website</option>
                <option value="Laravel">Laravel</option>
                <option value="Automation">Automation</option>
            </select>

            <select name="contact_channel" class="px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="WhatsApp">WhatsApp</option>
                <option value="Instagram">Instagram</option>
                <option value="Email">Email</option>
                <option value="Call">Call</option>
            </select>

            <input type="text" name="website_url" placeholder="Website (optional)" class="w-32 px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">

            <input type="text" name="problem_found" placeholder="Problem / Need (optional)" class="w-44 px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">

            <input type="date" name="follow_up_date" title="Follow-up Date (optional)" class="w-32 px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">

            <select name="currency" class="px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-bold">
                <option value="INR">₹ INR</option>
                <option value="AED">AED</option>
                <option value="USD">$ USD</option>
            </select>

            <input type="number" step="0.01" min="0" name="deal_amount" placeholder="Deal Amount (optional)" class="w-36 px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">

            <select name="status" class="px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="New">New Lead</option>
                <option value="Contacted">Contacted</option>
                <option value="Proposal">Proposal</option>
                <option value="Won">Won</option>
                <option value="Lost">Lost</option>
            </select>

            <input type="hidden" name="response" value="No response">

            <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-sm whitespace-nowrap">
                + Add Row
            </button>
        </form>
    </div>

    <!-- VIEW MODE: KANBAN BOARD matching Image 4 -->
    @if($viewMode === 'kanban')
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 overflow-x-auto pb-4">
            @php
                $columnDotColors = [
                    'New' => 'bg-gray-400',
                    'Contacted' => 'bg-blue-500',
                    'Proposal' => 'bg-purple-500',
                    'Won' => 'bg-emerald-500',
                    'Lost' => 'bg-red-500',
                ];
                $columnDisplayNames = [
                    'New' => 'New Lead',
                    'Contacted' => 'Contacted',
                    'Proposal' => 'Proposal',
                    'Won' => 'Won',
                    'Lost' => 'Lost',
                ];
                $nextStages = [
                    'New' => 'Contacted',
                    'Contacted' => 'Proposal',
                    'Proposal' => 'Won',
                ];
            @endphp

            @foreach($allStatuses as $st)
                <div class="flex flex-col rounded-2xl bg-gray-100/60 dark:bg-gray-800/40 border border-gray-200/80 dark:border-gray-700 p-3 min-w-[260px] min-h-[550px]"
                     ondragover="onKanbanDragOver(event)"
                     ondragleave="onKanbanDragLeave(event)"
                     ondrop="onKanbanDrop(event, '{{ $st }}')"
                     data-status-column="{{ $st }}">
                    
                    <!-- Column Header matching Image 4 -->
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $columnDotColors[$st] }}"></span>
                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $columnDisplayNames[$st] }}</span>
                            <span data-column-count="{{ $st }}" class="text-xs font-semibold text-gray-400">{{ $kanbanColumns[$st]->count() }}</span>
                        </div>
                        <button onclick="openLeadModalWithStage('{{ $st }}')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Cards Container -->
                    <div class="space-y-3.5 flex-1" id="cards-container-{{ $st }}">
                        @forelse($kanbanColumns[$st] as $lead)
                            @php
                                $channelBadge = match($lead->contact_channel) {
                                    'Instagram' => 'bg-pink-50 text-pink-700 border-pink-200 dark:bg-pink-500/10 dark:text-pink-300 dark:border-pink-500/20',
                                    'WhatsApp' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/20',
                                    'Email' => 'bg-cyan-50 text-cyan-700 border-cyan-200 dark:bg-cyan-500/10 dark:text-cyan-300 dark:border-cyan-500/20',
                                    default => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-300 dark:border-blue-500/20',
                                };
                                $channelIcon = match($lead->contact_channel) {
                                    'Instagram' => 'instagram',
                                    'WhatsApp' => 'message-circle',
                                    'Email' => 'mail',
                                    default => 'phone',
                                };
                            @endphp
                            <div class="crm-lead-card bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm {{ in_array($st, ['Won', 'Lost']) ? 'cursor-default' : 'cursor-grab active:cursor-grabbing hover:shadow-md' }} transition-shadow relative"
                                 draggable="{{ in_array($st, ['Won', 'Lost']) ? 'false' : 'true' }}"
                                 ondragstart="onKanbanDragStart(event, {{ $lead->id }}, '{{ $st }}', {{ (float)$lead->deal_amount }}, {{ (float)$lead->amount_received }}, {{ (float)$lead->pending_payment }}, '{{ addslashes($lead->business_name) }}', '{{ $lead->currency }}', '{{ $lead->currency_symbol }}')"
                                 id="lead-card-{{ $lead->id }}">
                                
                                <!-- Card Header: Business + Channel -->
                                <div class="flex items-start justify-between gap-2">
                                    <a href="{{ route('admin.crm.leads.show', $lead->id) }}" class="font-bold text-xs text-gray-900 dark:text-white hover:text-indigo-600 line-clamp-1">
                                        {{ $lead->business_name }}
                                    </a>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold border {{ $channelBadge }}">
                                        <i data-lucide="{{ $channelIcon }}" class="w-3 h-3"></i>
                                        {{ $lead->contact_channel }}
                                    </span>
                                </div>

                                <!-- Market & Service Tags -->
                                <div class="flex items-center gap-1.5 mt-2 text-[10px]">
                                    <span class="px-2 py-0.5 rounded font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                        {{ $lead->market }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                        {{ $lead->service }}
                                    </span>
                                </div>

                                <!-- Deal Amount Tag -->
                                <div class="mt-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-black bg-emerald-50 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-500/20">
                                        <span class="text-[10px] uppercase font-bold text-emerald-600">DEAL:</span>
                                        <span data-deal-amount-badge="{{ $lead->id }}">{{ $lead->formatted_deal_amount }}</span>
                                        <i data-lucide="edit-2" class="w-2.5 h-2.5 ml-0.5 text-emerald-600 opacity-60"></i>
                                    </span>
                                </div>

                                <!-- Identified Need -->
                                <div class="mt-3">
                                    <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider">IDENTIFIED NEED:</span>
                                    <p class="text-[11px] text-gray-600 dark:text-gray-300 line-clamp-2 mt-0.5">
                                        {{ $lead->problem_found ?? 'Audit discovery in progress' }}
                                    </p>
                                </div>

                                <!-- Follow-up Date -->
                                <div class="mt-3 flex items-center justify-between text-[11px] text-gray-400">
                                    <div class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        <span>{{ $lead->follow_up_date ? $lead->follow_up_date->format('M d, Y') : 'No follow-up' }}</span>
                                    </div>
                                    @if($lead->website_url)
                                        <a href="{{ $lead->website_url }}" target="_blank" class="hover:text-indigo-600 flex items-center gap-0.5">
                                            <span>Site</span>
                                            <i data-lucide="external-link" class="w-2.5 h-2.5"></i>
                                        </a>
                                    @endif
                                </div>

                                <!-- Response & Sent Badges -->
                                <div class="mt-3 pt-2.5 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-[10px]">
                                    <span class="inline-flex items-center gap-1 font-medium {{ $lead->response === 'Interested' ? 'text-emerald-600' : 'text-gray-400' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $lead->response === 'Interested' ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                        {{ $lead->response }}
                                    </span>

                                    <span class="px-2 py-0.5 rounded font-medium {{ $lead->message_sent ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                        {{ $lead->message_sent ? '✓ Sent' : 'Draft' }}
                                    </span>
                                </div>

                                <!-- Bottom Action Row: Edit/Delete & Next Stage Button matching Image 4 -->
                                <div class="mt-3 pt-2.5 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-gray-400">
                                        <a href="{{ route('admin.crm.leads.show', $lead->id) }}" class="hover:text-indigo-600" title="Edit lead">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </a>
                                        @php
                                            $hasPayments = $lead->payments->isNotEmpty();
                                            $isWon = $lead->status === 'Won';
                                            $isDeleteBlocked = $hasPayments || $isWon;
                                            $deleteReason = $hasPayments 
                                                ? "Cannot delete: {$lead->payments->count()} recorded payment(s) in financial ledger." 
                                                : ($isWon ? "Cannot delete a Won deal." : "");
                                        @endphp
                                        @if($isDeleteBlocked)
                                            <span class="text-gray-300 dark:text-gray-600 cursor-not-allowed" title="{{ $deleteReason }}">
                                                <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                                            </span>
                                        @else
                                            <form action="{{ route('admin.crm.leads.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Delete this lead?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="hover:text-red-600" title="Delete lead">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    @if(isset($nextStages[$st]))
                                        <button onclick="advanceLeadStage({{ $lead->id }}, '{{ $nextStages[$st] }}', {{ (float)$lead->deal_amount }}, {{ (float)$lead->amount_received }}, {{ (float)$lead->pending_payment }}, '{{ addslashes($lead->business_name) }}', '{{ $lead->currency }}', '{{ $lead->currency_symbol }}')" type="button" class="px-3 py-1 rounded-lg text-[11px] font-bold bg-[#0f172a] hover:bg-indigo-950 text-white shadow-sm flex items-center gap-1 transition-colors">
                                            <span>{{ $nextStages[$st] }}</span>
                                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <!-- Empty Column State matching Image 4 -->
                            <div class="p-8 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 text-center">
                                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 flex items-center justify-center mx-auto mb-2">
                                    <i data-lucide="layers" class="w-5 h-5"></i>
                                </div>
                                <div class="text-xs font-semibold text-gray-500">No leads in {{ $columnDisplayNames[$st] }}</div>
                                <button onclick="openLeadModalWithStage('{{ $st }}')" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline mt-1 block mx-auto">
                                    + Add First Lead
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

    <!-- VIEW MODE: TABLE -->
    @else
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#0f172a] text-gray-300 uppercase text-[10px] tracking-wider font-bold">
                            <th class="px-5 py-3.5">Business Name</th>
                            <th class="px-4 py-3.5">Market & Service</th>
                            <th class="px-4 py-3.5">Channel</th>
                            <th class="px-4 py-3.5">Pipeline Status</th>
                            <th class="px-4 py-3.5">Deal Amount</th>
                            <th class="px-4 py-3.5">Amount Received</th>
                            <th class="px-4 py-3.5">Pending Payment</th>
                            <th class="px-4 py-3.5">Payment Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($leads as $lead)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('admin.crm.leads.show', $lead->id) }}" class="font-bold text-gray-900 dark:text-white hover:text-indigo-600 block">
                                        {{ $lead->business_name }}
                                    </a>
                                    @if($lead->website_url)
                                        <a href="{{ $lead->website_url }}" target="_blank" class="text-[10px] text-gray-400 hover:text-indigo-500 truncate block max-w-xs">
                                            {{ $lead->website_url }}
                                        </a>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $lead->market }}</span>
                                    <span class="block text-[11px] text-gray-400">{{ $lead->service }}</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $lead->contact_channel }}</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <select 
                                        data-previous-status="{{ $lead->status }}"
                                        onfocus="this.dataset.previousStatus = this.value"
                                        onchange="handleLeadStatusChange({{ $lead->id }}, this.value, {{ (float)$lead->deal_amount }}, {{ (float)$lead->amount_received }}, {{ (float)$lead->pending_payment }}, '{{ addslashes($lead->business_name) }}', '{{ $lead->currency }}', '{{ $lead->currency_symbol }}', this)" 
                                        @if(in_array($lead->status, ['Won', 'Lost'])) disabled title="{{ $lead->status }} status is permanently locked" @endif
                                        class="text-[11px] font-bold px-2.5 py-1 rounded-full border-0 shadow-sm
                                        {{ in_array($lead->status, ['Won', 'Lost']) ? 'opacity-70 cursor-not-allowed' : 'cursor-pointer' }}
                                        {{ $lead->status === 'Won' ? 'bg-emerald-600 text-white' : 
                                           ($lead->status === 'Lost' ? 'bg-red-600 text-white' : 
                                           ($lead->status === 'Proposal' ? 'bg-purple-600 text-white' : 'bg-blue-600 text-white')) }}">
                                        <option value="New" {{ $lead->status === 'New' ? 'selected' : '' }}>New</option>
                                        <option value="Contacted" {{ $lead->status === 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                        <option value="Proposal" {{ $lead->status === 'Proposal' ? 'selected' : '' }}>Proposal</option>
                                        <option value="Won" {{ $lead->status === 'Won' ? 'selected' : '' }}>Won (Closed)</option>
                                        <option value="Lost" {{ $lead->status === 'Lost' ? 'selected' : '' }}>Lost</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3.5 font-bold text-gray-900 dark:text-white">
                                    {{ $lead->formatted_deal_amount }}
                                </td>
                                <td class="px-4 py-3.5 font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ $lead->currency_symbol }}{{ number_format($lead->amount_received, 2) }}
                                </td>
                                <td class="px-4 py-3.5 font-bold text-amber-500">
                                    {{ $lead->currency_symbol }}{{ number_format($lead->pending_payment, 2) }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold 
                                        {{ $lead->payment_status === 'Paid' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 
                                           ($lead->payment_status === 'Partial' ? 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-300' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-300') }}">
                                        {{ $lead->payment_status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right space-x-2">
                                    <a href="{{ route('admin.crm.leads.show', $lead->id) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 inline-block" title="View details">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    @php
                                        $hasPayments = $lead->payments->isNotEmpty();
                                        $isWon = $lead->status === 'Won';
                                        $isDeleteBlocked = $hasPayments || $isWon;
                                        $deleteReason = $hasPayments 
                                            ? "Cannot delete: {$lead->payments->count()} recorded payment(s) in financial ledger." 
                                            : ($isWon ? "Cannot delete a Won deal." : "");
                                    @endphp
                                    @if($isDeleteBlocked)
                                        <span class="p-1.5 text-gray-300 dark:text-gray-600 inline-block cursor-not-allowed" title="{{ $deleteReason }}">
                                            <i data-lucide="lock" class="w-4 h-4"></i>
                                        </span>
                                    @else
                                        <form action="{{ route('admin.crm.leads.destroy', $lead->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete lead?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 inline-block" title="Delete lead">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-8 text-gray-400">No leads found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($leads, 'links'))
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $leads->links() }}
                </div>
            @endif
        </div>
    @endif
</div>

<!-- Add New Lead Modal matching Image 5 -->
<div id="addLeadModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div onclick="closeLeadModal()" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative z-10 w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Add New Lead</h3>
                <p class="text-xs text-gray-400">Capture lead for outreach pipeline</p>
            </div>
            <button onclick="closeLeadModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.crm.leads.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Business Name *</label>
                <input type="text" name="business_name" required placeholder="e.g. Apex Logistics Hub" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Market</label>
                    <select name="market" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="India">India</option>
                        <option value="Dubai">Dubai</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Service</label>
                    <select name="service" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="Website">Website</option>
                        <option value="Laravel">Laravel</option>
                        <option value="Automation">Automation</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Channel</label>
                    <select name="contact_channel" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="WhatsApp">WhatsApp</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Email">Email</option>
                        <option value="Call">Call</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Website URL</label>
                    <input type="url" name="website_url" placeholder="https://example.com" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Problem Found / Need</label>
                <textarea name="problem_found" rows="2" placeholder="e.g. Broken checkout page, manual order entry spreadsheet" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Currency</label>
                    <select name="currency" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-bold">
                        <option value="INR">INR (₹)</option>
                        <option value="AED">AED</option>
                        <option value="USD">USD ($)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Deal Amount</label>
                    <input type="number" step="0.01" min="0" name="deal_amount" placeholder="Deal Amount" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Follow-up Date</label>
                    <input type="date" name="follow_up_date" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Notes (Optional)</label>
                    <input type="text" name="notes" placeholder="Initial client requirements..." class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="flex items-center pt-3">
                    <label class="inline-flex items-center text-xs font-medium text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="message_sent" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                        Outreach Message Sent
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Lead Response</label>
                    <select name="response" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="No response">No response</option>
                        <option value="Interested">Interested</option>
                        <option value="Not interested">Not interested</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Pipeline Stage</label>
                <select name="status" id="modalStageSelect" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="New" selected>New Lead</option>
                    <option value="Contacted">Contacted</option>
                    <option value="Proposal">Proposal</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeLeadModal()" type="button" class="px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm">
                    Create Lead
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Won Status Confirmation Modal matching Task 1 specification -->
<div id="wonConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div onclick="closeWonModal()" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <i data-lucide="award" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Mark Deal as Won (Closed)</h3>
                <p id="wonModalBizName" class="text-xs text-gray-400 font-semibold"></p>
            </div>
        </div>

        <p class="text-xs text-gray-600 dark:text-gray-300 mb-4">
            Confirm the contract terms to close this deal. Changing status to Won locks commercial terms and does not automatically record payments.
        </p>

        <!-- Financial Balance Overview -->
        <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-700 mb-4 space-y-2.5 text-xs">
            <!-- 1. Deal Amount (editable) -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="font-semibold text-gray-700 dark:text-gray-300">
                        Contract Deal Value (<span id="wonCurrencyLabel">INR</span>)
                    </label>
                    <span id="wonStatusPreviewBadge" class="px-2 py-0.5 rounded-full font-bold text-[10px] bg-gray-100 text-gray-700">
                        Pending
                    </span>
                </div>
                <div class="relative">
                    <span id="wonCurrencyPrefix" class="absolute left-3 top-2 font-bold text-gray-500 text-xs">₹</span>
                    <input type="number" step="0.01" min="0" id="wonDealAmountInput" oninput="recalcWonModal()" class="w-full text-sm font-bold pl-9 pr-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div id="wonDealAmountError" class="text-xs text-red-500 mt-1 hidden font-medium"></div>
            </div>

            <!-- Ledger Summary Grid -->
            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                <div class="p-2 rounded-lg bg-white dark:bg-gray-800/80 border border-gray-200/70 dark:border-gray-700">
                    <span class="text-[10px] text-gray-400 block font-semibold uppercase">Total Received</span>
                    <span id="wonModalReceivedAmount" class="text-xs font-bold text-emerald-600 dark:text-emerald-400">₹0.00</span>
                </div>
                <div class="p-2 rounded-lg bg-white dark:bg-gray-800/80 border border-gray-200/70 dark:border-gray-700">
                    <span class="text-[10px] text-gray-400 block font-semibold uppercase">Remaining Pending</span>
                    <span id="wonModalPendingAmount" class="text-xs font-bold text-amber-500">₹0.00</span>
                </div>
            </div>
        </div>

        <!-- Fully Settled Notice (shown when pending <= 0) -->
        <div id="wonFullyPaidNotice" class="mb-5 p-3 rounded-xl border border-emerald-200 dark:border-emerald-500/20 bg-emerald-50/50 dark:bg-emerald-500/10 text-xs text-emerald-700 dark:text-emerald-300 flex items-center gap-2 hidden">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0"></i>
            <span>Deal is 100% paid and settled. No further payments required.</span>
        </div>

        <!-- Optional Separate Payment Action Section -->
        <div id="wonOptionalPaymentSection" class="mb-5 p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-800 dark:text-gray-200">
                <input type="checkbox" id="wonRecordPaymentCheckbox" onchange="toggleWonPaymentFields()" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <span>Also record an upfront / advance payment received now</span>
            </label>

            <div id="wonPaymentFields" class="mt-3 space-y-3 hidden pt-3 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-[11px] font-semibold text-gray-600 dark:text-gray-400">Amount Received Today *</label>
                        <span id="wonMaxPayableNotice" class="text-[10px] text-gray-400"></span>
                    </div>
                    <div class="relative">
                        <span id="wonReceivedCurrencyPrefix" class="absolute left-3 top-2 font-bold text-gray-500 text-xs">₹</span>
                        <input type="number" step="0.01" min="0.01" id="wonAmountReceivedInput" placeholder="0.00" oninput="recalcWonPaymentPreview()" class="w-full text-xs font-bold pl-9 pr-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div id="wonPaymentError" class="text-xs text-red-500 mt-1 hidden font-medium"></div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-[11px] font-semibold text-gray-600 dark:text-gray-400 block mb-1">Payment Date</label>
                        <input type="date" id="wonPaymentDateInput" value="{{ date('Y-m-d') }}" class="w-full text-xs py-1.5 px-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-gray-600 dark:text-gray-400 block mb-1">Realized Cash (Est)</label>
                        <div id="wonReceivedInrPreview" class="text-xs font-black text-emerald-600 dark:text-emerald-400 py-1.5 px-2 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg border border-emerald-200 dark:border-emerald-500/20">
                            ₹0.00
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-semibold text-gray-600 dark:text-gray-400 block mb-1">Payment Note (optional)</label>
                    <input type="text" id="wonPaymentNoteInput" placeholder="e.g. Initial deposit on signing" class="w-full text-xs py-1.5 px-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <button onclick="closeWonModal()" type="button" class="px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                Cancel
            </button>
            <button id="wonConfirmBtn" type="button" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm shadow-emerald-500/20">
                Confirm & Mark Won
            </button>
        </div>
    </div>
</div>

<script>
    const liveRates = {
        'INR': 1.0000,
        'AED': {{ (float) ($aedRate ?? 23.0) }},
        'USD': {{ (float) ($usdRate ?? 86.0) }}
    };

    function openLeadModal() {
        const stageSelect = document.getElementById('modalStageSelect');
        if (stageSelect) stageSelect.value = 'New';
        document.getElementById('addLeadModal').classList.remove('hidden');
    }
    function openLeadModalWithStage(stage) {
        const stageSelect = document.getElementById('modalStageSelect');
        if (stageSelect) {
            stageSelect.value = (stage === 'New Lead') ? 'New' : stage;
        }
        document.getElementById('addLeadModal').classList.remove('hidden');
    }
    function closeLeadModal() {
        document.getElementById('addLeadModal').classList.add('hidden');
    }

    let draggedLeadId = null;
    let draggedFromStatus = null;
    let draggedDealAmount = 0;
    let draggedBizName = '';
    let draggedCurrency = 'INR';
    let draggedSymbol = '₹';

    let activeWonLeadId = null;
    let activeWonCurrency = 'INR';
    let activeWonSymbol = '₹';
    let activeStatusSelect = null;

    let draggedLeadId = null;
    let draggedFromStatus = null;
    let draggedDealAmount = 0;
    let draggedReceivedAmount = 0;
    let draggedPendingAmount = 0;
    let draggedBizName = '';
    let draggedCurrency = 'INR';
    let draggedSymbol = '₹';

    let activeWonLeadId = null;
    let activeWonCurrency = 'INR';
    let activeWonSymbol = '₹';
    let activeWonReceivedSoFar = 0;
    let activeWonPendingBalance = 0;
    let activeStatusSelect = null;

    function onKanbanDragStart(e, leadId, currentStatus, dealAmount, receivedAmount, pendingAmount, bizName, currency, symbol) {
        if (currentStatus === 'Won' || currentStatus === 'Lost') {
            e.preventDefault();
            return false;
        }
        draggedLeadId = leadId;
        draggedFromStatus = currentStatus;
        draggedDealAmount = dealAmount;
        draggedReceivedAmount = receivedAmount || 0;
        draggedPendingAmount = pendingAmount || 0;
        draggedBizName = bizName;
        draggedCurrency = currency || 'INR';
        draggedSymbol = symbol || (currency === 'AED' ? 'AED ' : (currency === 'USD' ? '$' : '₹'));
        e.dataTransfer.setData('text/plain', leadId);
        e.dataTransfer.effectAllowed = 'move';
    }

    function onKanbanDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        e.currentTarget.classList.add('ring-2', 'ring-indigo-500/50');
    }

    function onKanbanDragLeave(e) {
        e.currentTarget.classList.remove('ring-2', 'ring-indigo-500/50');
    }

    function onKanbanDrop(e, targetStatus) {
        e.preventDefault();
        e.currentTarget.classList.remove('ring-2', 'ring-indigo-500/50');
        if (!draggedLeadId || draggedFromStatus === targetStatus) return;

        advanceLeadStage(draggedLeadId, targetStatus, draggedDealAmount, draggedReceivedAmount, draggedPendingAmount, draggedBizName, draggedCurrency, draggedSymbol);
    }

    function advanceLeadStage(leadId, targetStatus, currentDealAmount, receivedAmount, pendingAmount, bizName, currency, symbol) {
        if (targetStatus === 'Won') {
            openWonConfirmationModal(leadId, currentDealAmount, receivedAmount, pendingAmount, bizName, currency, symbol, null);
            return;
        }

        executeStatusUpdate(leadId, targetStatus, null, null);
    }

    function handleLeadStatusChange(leadId, newStatus, currentDealAmount, receivedAmount, pendingAmount, bizName, currency, symbol, selectEl) {
        if (newStatus === 'Won') {
            openWonConfirmationModal(leadId, currentDealAmount, receivedAmount, pendingAmount, bizName, currency, symbol, selectEl);
            return;
        }

        executeStatusUpdate(leadId, newStatus, null, selectEl);
    }

    function openWonConfirmationModal(leadId, currentDealAmount, receivedAmount, pendingAmount, bizName, currency, symbol, selectEl) {
        activeStatusSelect = selectEl;
        activeWonLeadId = leadId;
        activeWonCurrency = currency || 'INR';
        activeWonSymbol = symbol || (currency === 'AED' ? 'AED ' : (currency === 'USD' ? '$' : '₹'));
        activeWonReceivedSoFar = parseFloat(receivedAmount) || 0;
        activeWonPendingBalance = parseFloat(pendingAmount) || Math.max(0, (currentDealAmount || 0) - activeWonReceivedSoFar);

        document.getElementById('wonModalBizName').textContent = bizName + ' (' + activeWonCurrency + ')';
        document.getElementById('wonCurrencyLabel').textContent = activeWonCurrency;
        document.getElementById('wonCurrencyPrefix').textContent = activeWonSymbol;
        document.getElementById('wonReceivedCurrencyPrefix').textContent = activeWonSymbol;

        document.getElementById('wonDealAmountInput').value = currentDealAmount || 0;
        document.getElementById('wonDealAmountError').classList.add('hidden');

        // Reset optional payment fields
        const paymentCheckbox = document.getElementById('wonRecordPaymentCheckbox');
        paymentCheckbox.checked = false;
        document.getElementById('wonPaymentFields').classList.add('hidden');
        document.getElementById('wonAmountReceivedInput').value = '';
        document.getElementById('wonPaymentError').classList.add('hidden');
        document.getElementById('wonPaymentNoteInput').value = '';

        recalcWonModal();

        document.getElementById('wonConfirmBtn').disabled = false;
        document.getElementById('wonConfirmBtn').textContent = 'Confirm & Mark Won';

        document.getElementById('wonConfirmBtn').onclick = function() {
            submitWonConfirmation();
        };

        document.getElementById('wonConfirmModal').classList.remove('hidden');
    }

    function toggleWonPaymentFields() {
        const checked = document.getElementById('wonRecordPaymentCheckbox').checked;
        const fields = document.getElementById('wonPaymentFields');
        const confirmBtn = document.getElementById('wonConfirmBtn');

        if (checked) {
            fields.classList.remove('hidden');
            confirmBtn.textContent = 'Confirm Won & Record Payment';
            recalcWonPaymentPreview();
        } else {
            fields.classList.add('hidden');
            confirmBtn.textContent = 'Confirm & Mark Won';
            document.getElementById('wonPaymentError').classList.add('hidden');
        }
    }

    function recalcWonModal() {
        const dealAmt = parseFloat(document.getElementById('wonDealAmountInput').value) || 0;
        const received = activeWonReceivedSoFar;
        const pending = Math.max(0, round2(dealAmt - received));
        activeWonPendingBalance = pending;

        document.getElementById('wonModalReceivedAmount').textContent = activeWonSymbol + received.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('wonModalPendingAmount').textContent = activeWonSymbol + pending.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        const statusBadge = document.getElementById('wonStatusPreviewBadge');
        const optionalSection = document.getElementById('wonOptionalPaymentSection');
        const fullyPaidNotice = document.getElementById('wonFullyPaidNotice');

        if (dealAmt > 0 && received >= dealAmt) {
            statusBadge.className = 'px-2 py-0.5 rounded-full font-bold text-[10px] bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300';
            statusBadge.textContent = 'Paid (100% Settled)';
            if (optionalSection) optionalSection.classList.add('hidden');
            if (fullyPaidNotice) fullyPaidNotice.classList.remove('hidden');
            const paymentCheckbox = document.getElementById('wonRecordPaymentCheckbox');
            if (paymentCheckbox && paymentCheckbox.checked) {
                paymentCheckbox.checked = false;
                toggleWonPaymentFields();
            }
        } else if (received > 0) {
            statusBadge.className = 'px-2 py-0.5 rounded-full font-bold text-[10px] bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300';
            statusBadge.textContent = 'Partial';
            if (optionalSection) optionalSection.classList.remove('hidden');
            if (fullyPaidNotice) fullyPaidNotice.classList.add('hidden');
        } else {
            statusBadge.className = 'px-2 py-0.5 rounded-full font-bold text-[10px] bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
            statusBadge.textContent = 'Pending (Unpaid)';
            if (optionalSection) optionalSection.classList.remove('hidden');
            if (fullyPaidNotice) fullyPaidNotice.classList.add('hidden');
        }

        document.getElementById('wonAmountReceivedInput').max = pending > 0 ? pending : 0;
        document.getElementById('wonMaxPayableNotice').textContent = 'Max: ' + activeWonSymbol + pending.toLocaleString('en-IN', {minimumFractionDigits: 2});

        recalcWonPaymentPreview();
    }

    function recalcWonPaymentPreview() {
        const payAmt = parseFloat(document.getElementById('wonAmountReceivedInput').value) || 0;
        const rate = liveRates[activeWonCurrency] || 1.0;
        const inr = payAmt * rate;
        document.getElementById('wonReceivedInrPreview').textContent = '₹' + inr.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function round2(val) {
        return Math.round(val * 100) / 100;
    }

    async function submitWonConfirmation() {
        const dealAmt = parseFloat(document.getElementById('wonDealAmountInput').value);
        if (isNaN(dealAmt) || dealAmt < 0) {
            document.getElementById('wonDealAmountError').textContent = 'Please enter a valid deal amount (min 0).';
            document.getElementById('wonDealAmountError').classList.remove('hidden');
            return;
        }

        // Deal amount cannot be reduced below already received payments
        if (activeWonReceivedSoFar > 0 && round2(dealAmt) < round2(activeWonReceivedSoFar)) {
            document.getElementById('wonDealAmountError').textContent = 'Deal amount cannot be less than already received payments (' + activeWonSymbol + activeWonReceivedSoFar.toFixed(2) + ').';
            document.getElementById('wonDealAmountError').classList.remove('hidden');
            return;
        }
        document.getElementById('wonDealAmountError').classList.add('hidden');

        const recordPayment = document.getElementById('wonRecordPaymentCheckbox').checked;
        let paymentAmount = 0;
        let paymentDate = null;
        let paymentNote = null;

        if (recordPayment) {
            paymentAmount = parseFloat(document.getElementById('wonAmountReceivedInput').value) || 0;
            if (paymentAmount <= 0) {
                document.getElementById('wonPaymentError').textContent = 'Please enter a payment amount greater than zero.';
                document.getElementById('wonPaymentError').classList.remove('hidden');
                return;
            }
            if (round2(paymentAmount) > round2(activeWonPendingBalance)) {
                document.getElementById('wonPaymentError').textContent = 'Payment cannot exceed remaining pending balance (' + activeWonSymbol + activeWonPendingBalance.toFixed(2) + ').';
                document.getElementById('wonPaymentError').classList.remove('hidden');
                return;
            }
            document.getElementById('wonPaymentError').classList.add('hidden');
            paymentDate = document.getElementById('wonPaymentDateInput').value;
            paymentNote = document.getElementById('wonPaymentNoteInput').value;
        }

        const confirmBtn = document.getElementById('wonConfirmBtn');
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Updating...';

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        try {
            // 1. Update lead status to Won (pure status update, no automatic payment)
            const statusRes = await fetch(`{{ url('admin/crm/leads') }}/${activeWonLeadId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({
                    status: 'Won',
                    deal_amount: dealAmt,
                    _token: token
                })
            });

            const statusData = await statusRes.json();
            if (!statusRes.ok || !statusData.success) {
                alert(statusData.message || 'Failed to update lead status to Won.');
                confirmBtn.disabled = false;
                confirmBtn.textContent = recordPayment ? 'Confirm Won & Record Payment' : 'Confirm & Mark Won';
                return;
            }

            // 2. If optional payment was explicitly requested, record it via standard payment endpoint
            if (recordPayment && paymentAmount > 0) {
                confirmBtn.textContent = 'Recording Payment...';
                const payRes = await fetch(`{{ url('admin/crm/leads') }}/${activeWonLeadId}/payments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        amount: paymentAmount,
                        paid_on: paymentDate,
                        note: paymentNote || 'Payment upon Won deal closure',
                        _token: token
                    })
                });

                const payData = await payRes.json();
                if (!payRes.ok || !payData.success) {
                    alert('Lead marked as Won, but payment recording failed: ' + (payData.message || 'Validation error'));
                    location.reload();
                    return;
                }
            }

            // Reload page to recompute all KPI metrics & status dropdowns
            location.reload();
        } catch (err) {
            console.error(err);
            alert('An unexpected network error occurred.');
            confirmBtn.disabled = false;
            confirmBtn.textContent = recordPayment ? 'Confirm Won & Record Payment' : 'Confirm & Mark Won';
        }
    }

    function closeWonModal() {
        document.getElementById('wonConfirmModal').classList.add('hidden');
        if (activeStatusSelect && activeStatusSelect.dataset.previousStatus) {
            activeStatusSelect.value = activeStatusSelect.dataset.previousStatus;
        }
    }

    const kanbanNextStages = {
        'New': 'Contacted',
        'Contacted': 'Proposal',
        'Proposal': 'Won',
        'Won': null,
        'Lost': null
    };

    function executeStatusUpdate(leadId, targetStatus, dealAmount, selectEl) {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        const payload = { status: targetStatus, _token: token };
        if (dealAmount !== null && dealAmount !== undefined) {
            payload.deal_amount = dealAmount;
        }

        fetch(`{{ url('admin/crm/leads') }}/${leadId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // When marked Won, reload to recompute revenue badges & payments ledger
                if (targetStatus === 'Won') {
                    location.reload();
                    return;
                }

                if (selectEl) {
                    selectEl.dataset.previousStatus = targetStatus;
                    location.reload();
                    return;
                }

                // Pure AJAX DOM card movement without page reload for non-Won kanban moves
                const card = document.getElementById('lead-card-' + leadId);
                const targetContainer = document.getElementById('cards-container-' + targetStatus);

                if (card && targetContainer) {
                    if (data.lead && data.lead.formatted_deal_amount) {
                        const dealBadge = card.querySelector(`[data-deal-amount-badge="${leadId}"]`);
                        if (dealBadge) dealBadge.textContent = data.lead.formatted_deal_amount;
                    }

                    targetContainer.prepend(card);
                    card.setAttribute('ondragstart', `onKanbanDragStart(event, ${leadId}, '${targetStatus}', ${data.lead.deal_amount || 0}, ${data.lead.amount_received || 0}, ${data.lead.pending_payment || 0}, '${addslashes(draggedBizName)}', '${draggedCurrency}', '${draggedSymbol}')`);

                    const actionRow = card.querySelector('.mt-3.pt-2\\.5.border-t:last-child');
                    if (actionRow) {
                        const existingBtn = actionRow.querySelector('button[onclick*="advanceLeadStage"]');
                        const next = kanbanNextStages[targetStatus];
                        if (next) {
                            if (existingBtn) {
                                existingBtn.setAttribute('onclick', `advanceLeadStage(${leadId}, '${next}', ${data.lead.deal_amount || 0}, ${data.lead.amount_received || 0}, ${data.lead.pending_payment || 0}, '${addslashes(draggedBizName)}', '${draggedCurrency}', '${draggedSymbol}')`);
                                const textSpan = existingBtn.querySelector('span');
                                if (textSpan) textSpan.textContent = next;
                            } else {
                                const newBtn = document.createElement('button');
                                newBtn.setAttribute('type', 'button');
                                newBtn.setAttribute('onclick', `advanceLeadStage(${leadId}, '${next}', ${data.lead.deal_amount || 0}, ${data.lead.amount_received || 0}, ${data.lead.pending_payment || 0}, '${addslashes(draggedBizName)}', '${draggedCurrency}', '${draggedSymbol}')`);
                                newBtn.className = 'px-3 py-1 rounded-lg text-[11px] font-bold bg-[#0f172a] hover:bg-indigo-950 text-white shadow-sm flex items-center gap-1 transition-colors';
                                newBtn.innerHTML = `<span>${next}</span><i data-lucide="arrow-right" class="w-3 h-3"></i>`;
                                actionRow.appendChild(newBtn);
                                if (window.lucide) lucide.createIcons();
                            }
                        } else if (existingBtn) {
                            existingBtn.remove();
                        }
                    }

                    // Pulse green highlight animation
                    card.classList.add('ring-2', 'ring-emerald-500');
                    setTimeout(() => card.classList.remove('ring-2', 'ring-emerald-500'), 1500);

                    // Dynamic column count update
                    updateKanbanColumnCounts();
                } else {
                    location.reload();
                }
            } else {
                alert('Update failed: ' + (data.message || 'Error'));
                if (selectEl && selectEl.dataset.previousStatus) {
                    selectEl.value = selectEl.dataset.previousStatus;
                }
                closeWonModal();
            }
        })
        .catch(err => {
            console.error(err);
            alert('Server error.');
            if (selectEl && selectEl.dataset.previousStatus) {
                selectEl.value = selectEl.dataset.previousStatus;
            }
            closeWonModal();
        });
    }

    function updateKanbanColumnCounts() {
        document.querySelectorAll('[data-status-column]').forEach(col => {
            const st = col.getAttribute('data-status-column');
            const countEl = col.querySelector(`[data-column-count="${st}"]`);
            const container = document.getElementById('cards-container-' + st);
            if (countEl && container) {
                const count = container.querySelectorAll('.crm-lead-card').length;
                countEl.textContent = count;
            }
        });
    }

    function addslashes(string) {
        return (string + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
    }
</script>
@endsection
