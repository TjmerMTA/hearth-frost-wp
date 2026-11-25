<?php
/**
 * Default texts. Every value here can be changed in Appearance → Customize;
 * these are only what a fresh install shows.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hf_defaults() {
	return array(
		// Company & contacts.
		'company'        => 'Hearth & Frost',
		'company_long'   => 'Hearth & Frost Appliance Service',
		'tagline'        => 'Luxury appliance service',
		'phone'          => '(800) 555-0142',
		'email'          => 'service@hearthandfrost.example',
		'address'        => '1200 Example Avenue, Suite 100, Irvine, CA 92618',
		'hours'          => 'Mon – Fri, 8 am – 5 pm',
		'region'         => 'Southern California',
		'request_email'  => '',
		'topbar'         => 'Specialists in Sub-Zero, Wolf & Cove',
		'trademark'      => 'Sub-Zero, Wolf and Cove are registered trademarks of their respective owner. {{company}} is an independent service company and is not affiliated with or endorsed by the manufacturer.',

		// Hero.
		'hero_eyebrow'   => 'Sub-Zero · Wolf · Cove service',
		'hero_title'     => 'Service worthy of *the finest* kitchens',
		'hero_text'      => 'Factory-trained technicians, genuine parts and an unhurried, meticulous approach — for the appliances at the heart of your home.',
		'hero_image'     => 'hero-kitchen.jpg',
		'hero_note'      => 'Same-week appointments across {{region}}',

		// Intro / tri-category band (dark).
		'intro_title'    => 'Specialists, *not* generalists',
		'intro_text'     => 'We work on one family of appliances and nothing else. That focus means our technicians know every sealed system, burner and control board by heart — and arrive with the right parts on the first visit far more often than not.',
		'cat1_title'     => 'Refrigeration',
		'cat1_text'      => 'Built-in, column, integrated and wine storage.',
		'cat1_image'     => 'refrigeration.jpg',
		'cat2_title'     => 'Cooking',
		'cat2_text'      => 'Ranges, rangetops, cooktops and wall ovens.',
		'cat2_image'     => 'range.jpg',
		'cat3_title'     => 'Dishwashing',
		'cat3_text'      => 'Integrated and panel-ready dishwashers.',
		'cat3_image'     => 'dishwasher.jpg',

		// Numbers.
		'stats'          => "38|Years of service\n60,000+|Appliances repaired\n4.9|Average customer rating\n92%|Fixed on the first visit",

		// Services block.
		'services_title' => 'How we can *help*',
		'services_text'  => 'Repair, maintenance and warranty service for every appliance in a luxury kitchen — indoors and out.',

		// Why us (split card).
		'why_title'      => 'A higher standard of *care*',
		'why_text'       => 'Your kitchen was designed with intention. We treat it that way — from the first phone call to the last wipe of the counter.',
		'why_points'     => "Factory-trained technicians|Continuous training on current and legacy models.\nGenuine OEM parts|Original components only, stocked on our vans.\nRespect for your home|Shoe covers, protective mats and a clean finish.\nWork that is guaranteed|Every repair is backed by our workmanship warranty.",
		'why_image'      => 'technician.jpg',

		// Process.
		'process_title'  => 'From request to *resolved*',
		'process'        => "Request a visit|Book online or call. Share the model and serial number if you have them.\nPrecise diagnosis|A technician inspects the appliance and explains findings and price before any work.\nRepair with genuine parts|Most repairs are completed the same visit with parts from our van.\nFollow-up & warranty|You receive a detailed invoice and our workmanship warranty.",

		// Reviews.
		'reviews_title'  => 'Trusted in the *finest* homes',
		'reviews_image'  => 'black-marble.jpg',

		// Areas.
		'areas_title'    => 'Serving *{{region}}*',
		'areas_text'     => 'Our technicians cover the coast and the inland valleys every weekday. Not sure we reach you? Call and we will tell you right away.',
		'areas'          => "Los Angeles\nOrange County\nSan Diego\nSanta Barbara\nVentura County\nRiverside County\nSan Bernardino County\nPalm Springs & Desert Cities",
		'areas_image'    => 'marble-kitchen.jpg',

		// FAQ.
		'faq_title'      => 'Questions, *answered*',
		'faq'            => "Do you handle warranty repairs?|Yes. We service appliances both in and out of the manufacturer warranty and can process eligible warranty claims for you. Have your model and serial number ready when you book.\nWhat does a diagnosis visit cost?|A diagnosis visit has a flat fee that is quoted when you book. If you approve the repair, we tell you the full price before any work begins.\nDo you work on older models?|Yes. We service current and legacy models, including units that are twenty years old or more. Parts availability for very old models is confirmed before we schedule.\nHow soon can a technician come?|In most of our service area we offer appointments within the same week, often sooner for urgent refrigeration problems.\nDo you use genuine parts?|Always. We install original manufacturer parts only, which protects performance, safety and any remaining warranty.",

		// Final call to action.
		'cta_title'      => 'Bring your kitchen back to *its standard*',
		'cta_text'       => 'Tell us what is happening and we will arrange a visit at a time that suits you.',
		'cta_image'      => 'flame.jpg',

		// Form.
		'form_ok'        => 'Thank you. Your request has been received — we will call you shortly to confirm the appointment.',
	);
}

/** Default reviews (seeded into "Reviews" on first run). */
function hf_default_reviews() {
	return array(
		array( 'The technician arrived on time, protected the floor before he started and had our Sub-Zero cooling again within the hour. Calm, precise and genuinely courteous.', 'Catherine M.', 'Newport Beach' ),
		array( 'Our Wolf range had an ignition problem two other companies could not find. Hearth & Frost diagnosed it in twenty minutes and fixed it the same visit.', 'David R.', 'Pasadena' ),
		array( 'We specify these appliances for every project. This is the service team we recommend to our clients without hesitation.', 'Elena V.', 'Interior designer, La Jolla' ),
		array( 'Clear price before any work, genuine parts and a spotless kitchen afterwards. Exactly what you hope for and rarely get.', 'Michael T.', 'Santa Monica' ),
		array( 'The wine column was fluctuating by several degrees. They replaced a sensor, explained everything and followed up a week later.', 'Sophia L.', 'Montecito' ),
	);
}

/** Default services: slug => [ image, order ]. Texts come from content/services/<slug>.html. */
function hf_default_services() {
	return array(
		'refrigeration'    => array( 'refrigeration.jpg', 1 ),
		'ranges-cooktops'  => array( 'range.jpg', 2 ),
		'wall-ovens'       => array( 'ovens.jpg', 3 ),
		'dishwashers'      => array( 'dishwasher.jpg', 4 ),
		'outdoor-kitchens' => array( 'outdoor.jpg', 5 ),
		'maintenance'      => array( 'knobs.jpg', 6 ),
	);
}
