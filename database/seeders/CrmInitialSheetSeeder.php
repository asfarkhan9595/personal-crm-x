<?php

namespace Database\Seeders;

use App\Models\CrmLead;
use App\Models\CrmPayment;
use App\Services\CrmGoogleSheetService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CrmInitialSheetSeeder extends Seeder
{
    /**
     * Seed initial 8 leads and 3 payments.
     * If Google Sheet is connected and populated, imports directly;
     * otherwise seeds the standard initial dataset matching acceptance criteria.
     */
    public function run(): void
    {
        $sheetService = app(CrmGoogleSheetService::class);
        if ($sheetService->isConfigured()) {
            $result = $sheetService->importFromSheet();
            if ($result['success'] && CrmLead::count() > 0) {
                $this->command?->info($result['message']);
                return;
            }
        }

        // Standard Initial 8 Leads
        $leadsData = [
            [
                'business_name' => 'Nova Health',
                'market' => 'India',
                'service' => 'Laravel',
                'contact_channel' => 'WhatsApp',
                'website_url' => 'https://novahealth.example.com',
                'problem_found' => 'Slow booking portal and missing patient reminders',
                'message_sent' => true,
                'response' => 'Interested',
                'follow_up_date' => Carbon::today()->addDays(2),
                'status' => 'Won',
                'currency' => 'INR',
                'deal_amount' => 90000.00,
                'notes' => 'Healthcare clinic booking portal. Deal closed at 90k INR in milestones.',
            ],
            [
                'business_name' => 'Al Noor Luxury Real Estate',
                'market' => 'Dubai',
                'service' => 'Website',
                'contact_channel' => 'WhatsApp',
                'website_url' => 'https://alnoor-properties.ae',
                'problem_found' => 'Outdated property catalogue with no WhatsApp lead capture',
                'message_sent' => true,
                'response' => 'Interested',
                'follow_up_date' => Carbon::today()->addDays(1),
                'status' => 'Proposal',
                'currency' => 'AED',
                'deal_amount' => 5000.00,
                'notes' => 'High-ticket Dubai real estate broker.',
            ],
            [
                'business_name' => 'CloudScale Analytics Inc',
                'market' => 'Other',
                'service' => 'Laravel',
                'contact_channel' => 'Email',
                'website_url' => 'https://cloudscale-us.io',
                'problem_found' => 'API throughput bottleneck on high concurrency',
                'message_sent' => true,
                'response' => 'Interested',
                'follow_up_date' => Carbon::today()->addDays(4),
                'status' => 'Proposal',
                'currency' => 'USD',
                'deal_amount' => 2500.00,
                'notes' => 'US SaaS platform backend optimization.',
            ],
            [
                'business_name' => 'Apex Logistics Solutions',
                'market' => 'India',
                'service' => 'Automation',
                'contact_channel' => 'Call',
                'website_url' => 'https://apexlogistics.in',
                'problem_found' => 'Manual dispatch tracking in Excel spreadsheets',
                'message_sent' => true,
                'response' => 'Interested',
                'follow_up_date' => Carbon::today(),
                'status' => 'Contacted',
                'currency' => 'INR',
                'deal_amount' => 45000.00,
                'notes' => 'Requires automated webhook dispatch sync.',
            ],
            [
                'business_name' => 'Oasis Hospitality Dubai',
                'market' => 'Dubai',
                'service' => 'Automation',
                'contact_channel' => 'Instagram',
                'website_url' => 'https://oasishospitality.ae',
                'problem_found' => 'Lost DM inquiries and manual reservation confirmations',
                'message_sent' => true,
                'response' => 'No response',
                'follow_up_date' => Carbon::today()->subDay(), // overdue
                'status' => 'Contacted',
                'currency' => 'AED',
                'deal_amount' => 3500.00,
                'notes' => 'Needs automated Instagram DM to WhatsApp booking flow.',
            ],
            [
                'business_name' => 'Horizon Dental Care',
                'market' => 'India',
                'service' => 'Website',
                'contact_channel' => 'WhatsApp',
                'website_url' => 'https://horizondental.in',
                'problem_found' => 'Non-responsive website with broken appointment forms',
                'message_sent' => false,
                'response' => 'No response',
                'follow_up_date' => Carbon::today()->addDays(3),
                'status' => 'New',
                'currency' => 'INR',
                'deal_amount' => 35000.00,
                'notes' => 'Lead discovered via Google Maps audit.',
            ],
            [
                'business_name' => 'Swift Courier Hub',
                'market' => 'India',
                'service' => 'Laravel',
                'contact_channel' => 'Call',
                'website_url' => 'https://swiftcourier.in',
                'problem_found' => 'Legacy PHP app needing Laravel 11/12 migration',
                'message_sent' => true,
                'response' => 'Interested',
                'follow_up_date' => Carbon::today()->addDays(5),
                'status' => 'New',
                'currency' => 'INR',
                'deal_amount' => 60000.00,
                'notes' => 'Interested in 3-week sprint rebuild.',
            ],
            [
                'business_name' => 'Prime Gulf Media',
                'market' => 'Dubai',
                'service' => 'Website',
                'contact_channel' => 'Email',
                'website_url' => 'https://primegulf.ae',
                'problem_found' => 'Portfolio site lacks case studies and video showcases',
                'message_sent' => true,
                'response' => 'Not interested',
                'follow_up_date' => null,
                'status' => 'Lost',
                'currency' => 'AED',
                'deal_amount' => 4000.00,
                'notes' => 'Currently engaged with an in-house designer.',
            ],
        ];

        foreach ($leadsData as $data) {
            CrmLead::updateOrCreate(['business_name' => $data['business_name']], $data);
        }

        // Initial 3 Payments
        $nova = CrmLead::where('business_name', 'Nova Health')->first();
        if ($nova && $nova->payments()->count() === 0) {
            // Milestone 1 (30k)
            CrmPayment::create([
                'lead_id' => $nova->id,
                'paid_on' => Carbon::today()->subDays(10),
                'currency' => 'INR',
                'amount' => 30000.00,
                'fx_rate' => 1.0000,
                'received_inr' => 30000.00,
                'note' => 'Initial 33% advance for discovery & wireframes',
            ]);

            // Milestone 2 (30k) -> Total received = 60,000, Pending = 30,000
            CrmPayment::create([
                'lead_id' => $nova->id,
                'paid_on' => Carbon::today()->subDays(3),
                'currency' => 'INR',
                'amount' => 30000.00,
                'fx_rate' => 1.0000,
                'received_inr' => 30000.00,
                'note' => 'Design sign-off milestone payment',
            ]);
        }

        $cloudscale = CrmLead::where('business_name', 'CloudScale Analytics Inc')->first();
        if ($cloudscale && $cloudscale->payments()->count() === 0) {
            // 3rd payment: $500 advance for audit @ rate 83.5
            CrmPayment::create([
                'lead_id' => $cloudscale->id,
                'paid_on' => Carbon::today()->subDays(7),
                'currency' => 'USD',
                'amount' => 500.00,
                'fx_rate' => 83.5000,
                'received_inr' => 41750.00,
                'note' => 'Initial backend profiling & audit fee ($500 @ 83.50 locked)',
            ]);
        }

        $this->command?->info('Initial 8 leads and 3 payments seeded successfully.');
    }
}
