=== Typeform | Engage your audience with beautiful forms, surveys, and quizzes ===
Contributors: typeform, michaelsolati
Tags: typeform, forms, surveys, quizzes, form builder, survey builder, quiz builder, custom forms, mobile forms, payment forms, order forms, feedback forms, enquiry forms, stripe, dropbox, google sheets, mailchimp, salesforce, hubspot, activecampaign, infusionsoft, asana, hipchat, slack, trello, zendesk
Requires at least: 5.0
Tested up to: 5.4.2
License: Apache-2.0
License URI: https://directory.fsf.org/wiki/License:Apache-2.0

Create beautiful online forms, surveys, quizzes, and much more.

== Description ==
“Oh, goodie. An online form,” said no one, ever.

But what if you could change that?

With Typeform, you can make forms and surveys people actually like filling in. The one-question-at-a-time interface feels more like a conversation—and gets better responses as a result. Get more leads, grow your contact list, collect feedback, and much more. 
 
Customize your typeform’s font, color scheme, background, and images to match your brand—and embed it seamlessly into your web page. Typeforms look and feel great on any device. 

------------

> **Important**
> This plugin is for embedding forms created over at Typeform. You can’t edit them or see responses inside WordPress.
> This plugin is incompatible with Wordpress 4. If you need support for Wordpress 4, please use [version 0.7.5](https://downloads.wordpress.org/plugin/typeform.0.7.5.zip).
> If you are upgrading from a version of this plugin prior to 1.0.0, your forms will appear as shortcodes in the editor. It will render the form on your website pages, however to edit your forms you will need to re-embed them with the new Gutenberg block plugin.

------------

= CREATE POWERFUL FORMS WITHOUT A LINE OF CODE =
Forms, surveys, quizzes, polls. Shopping carts? Yep, those too.
* Build conversations, one question at a time
* Add Logic Jumps to personalize and segment your audience
* Create engaging quizzes with the Calculator   
* Show people tailored endings based on their answers
* Move your data to the tools you love with our 30+ integrations (Slack, Hubspot, Mailchimp, monday.com, Dropbox, Zoho, etc)

= LOOK PROFESSIONAL. NO DESIGN SKILLS REQUIRED. =
* Pick one of our many templates or start from scratch
* Customize the look of your typeform, from button and text color to GIFs and background images.

= SEE METRICS AT A GLANCE AND GENERATE BEAUTIFUL REPORTS =
* Simple dashboard showing visits, responses, completion rate, and average completion time
* Stylish web report with PDF export
* Export responses to CSV and XLSX

= INTEGRATE WITH YOUR FAVORITE TOOLS =
Make sense of your data in the tools you already use:
* Move leads into your CRM or marketing tools such as Mailchimp, Hubspot, ActiveCampaign, Keap by Infusionsoft, Zoho, Pipedrive, and more.
* Send responses to Slack, Airtable, monday.com, and more—so you can collaborate with your team in real time.
* Ask for files and have them automatically appear in Dropbox or Box. 

[See all our integrations](https://www.typeform.com/connect/?utm_source=wordpressorg&utm_medium=referral&utm_campaign=wordpressorg_integration&utm_content=directory)

== Installation ==
1. Add the plugin folder to the /wp-content/plugins/ directory
2. Activate the plugin through the ‘Plugins’ menu in WordPress
3. Add your typeforms through the “Add block” button that has been added to post/pages editor aside of the Media Button
4. Add your typeform’s URL and select from the options available

== Frequently Asked Questions ==
* How can I add a typeform to my content?
 * Click the “Add block“ button, which opens a search field. Look for “Typeform” from your installed blocks. Once added, click on your new block and then click the Gear icon to open your Typeform block settings. Paste the URL of the typeform you want to embed, then choose between “standard” view and select the size of the frame that will contain your typeform, or “popup” view and configure the button that will trigger the typeform.
* How can I downgrade use this plugin with Wordpress 4?
 * This plugin is incompatible with Wordpress 4.  If you need support for Wordpress 4, please download [version 0.7.5](https://downloads.wordpress.org/plugin/typeform.0.7.5.zip).

== Changelog ==
= 1.3.2 =
* fix: support for new typeform ids in shortcode
= 1.3.1 =
* fix: make it work with new typeform ids
= 1.3.0 =
*  feat: **editor:** display block settings in page
*  refactor: use bearer for api calls, fixes CORS issue
*  refactor: move styling into own css file instead of inline
= 1.2.2 =
*  feat: provide better error notification
*  fix: address rendering of form in editor and form locking
= 1.2.1 =
*  chore: update dependencies
= 1.2.0 =
*  build: change directory of JS files
*  docs: **readme:**  add details about shortcode rendering
*  feat: add PHP function to handle rendering of `typeform_embed` shortcodes
= 1.1.1 =
*  fix: change name of PHP `scripts` function to prevent namespace conflict
= 1.1.0 =
*  chore: add changelog generator
*  docs: **readme:**  remove contributor
*  feat: **Login:**  add ability to fetch forms with OAuth login
= 1.0.0 =
* chore: write new plugin for gutenberg

== Upgrade Notice ==
If you are upgrading from a version of this plugin prior to 1.0.0, your forms will appear as shortcodes in the editor. It will render the form on your website pages, however to edit your forms you will need to re-embed them with the new Gutenberg block plugin.