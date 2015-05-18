﻿--
-- Dumping data for table `issue`
--

LOCK TABLES `issue` WRITE;
/*!40000 ALTER TABLE `issue` DISABLE KEYS */;
INSERT INTO `issue` VALUES ('accuracy',NULL,'The target text does not accurately reflect the source text, allowing for any differences authorized by specifications.','<li>Most cases of //accuracy// are addressed by one of the more specific subtypes listed below.</li><li>In Machine Translation literature, this category is typically referred to as “Adequacy”.</li>','','Accuracy'),('added-markup','markup','The target text has markup added with no corresponding markup in the source.','','<li>A source segment has no formatting tags, but the target has a set of italic tags.</li>','Added markup'),('addition','accuracy','The target text includes text not present in the source.','','<li>A translation includes portions of another translation that were inadvertently pasted into the document.</li>','Addition'),('agreement','word-form','Two or more words do not agree with respect to case, number, person, or other grammatical features','','<li>A text reads “They was expecting a report.”</li>','Agreement'),('ambiguity','content','The text is ambiguous in its meaning.','','<li>A text reads “I cannot recommend this too highly.” (The meaning can be that the speaker cannot make a good recommendation or that it is highly recommended.)</li>','Ambiguity'),('bold-italic','font','Bold or italics are used incorrectly.','','<li>A book title should have been italicized, but the italics were omitted.</li>','Bold/italic'),('broken-link','mechanical','A link or cross reference points to an incorrect or nonexistent location','','<li>An HTML document has an href that points to a file that does not exist.</li>','Broken link/cross-reference'),('call-outs-captions','graphics-tables','There are issues with call-outs (text within a graphic that identifies parts) or captions.','','<li>During localization the location of numbers used for call-outs has been shifted and the call-outs are no longer usable.</li>','Call-outs and captions'),('capitalization','spelling','Issues related to capitalization','','<li>The name <em>John Smith</em> is written as “john smith”</li>','Capitalization'),('character-encoding','mechanical','Characters are garbled due to incorrect application of an encoding.','','<li>A text document in UTF-8 encoding is opened as ISO Latin-1, resulting in all “upper ASCII” characters being garbled.</li>','Character encoding'),('color','overall-design','Colors are used incorrectly','','<li>Headings should be blue but are green instead.</li>','Color'),('company-style','style-guide','The text violates company/organization-specific style guidelines.','','<li>Company style states that passive sentences may not be used but the text uses passive sentences.</li>','Company style'),('compatibility',NULL,'The Compatibility extension contains items which may be used for compatibility with legacy metrics even though they would otherwise not be included in MQM. Most of these issue types are taken from the LISA QA Model documentation.','<li>Use of these categories is not recommended and these issue types are to be considered deprecated. They are included only for compatibility with legacy processes.</li><li>Since //compatibility// is not a coherent category, use of this category itself is not recommended in any circumstance, although the children categories listed above may be used for compatibility purposes.</li>','<li>A quality process checks the LISA QA Model issue “Book-building sequence” and it is included for compatibility with legacy processes</li>','Compatibility (Deprecated)'),('completeness','verity','The text is incomplete','<li>//completeness// refers to instances in which needed content is missing in the source language. For cases where material present in the source language is not present in a translation, //omission// should be used instead.</li>','<li>A process description leaves out key steps needed to complete the process, resulting in an incomplete description of the process.</li>','Completeness'),('content','fluency','Issues related to content, excluding presentational and/or mechanical issues','','<li>There is a problem with the presentation of information in the text</li>','Content'),('corpus-conformance','mechanical','The content is deemed to have a level of conformance to a reference corpus. The non-conformance type reflects the degree to which the text conforms to a reference corpus given an algorithm that combines several classes of error type to produce an aggregate rating.','<li>One example of this issue type might involve output from a quality estimation system that delivers a warning that a text has a very low quality estimation score.</li>','<li>A text reading “The harbour connected which to printer is busy or configared not properly” is flagged by a language analysis tool as suspect based on its lack of conformance to an existing corpus.</li>','Corpus conformance'),('date-format','locale-convention','A text uses a date format inappropriate for its locale.','','<li>An English text has “2012-06-07” instead of the expected “06/07/2012.”</li>','Date format'),('date-time','mistranslation','Dates or times do not match between source and target.','','<li>A German source text provides the date 09.02.09 (=February 9, 2009) but the English target renders it as September 2, 2009.</li><li>An English source text specifies a time of “4:40 PM” but this is rendered as 04:40 (=4:40 AM) in a German translation.</li>','Date/time'),('design',NULL,'There is a problem relating to design aspects (vs. linguistic aspects) of the content.','<li>Design issues may exist either in documentions in isolation (e.g., a second-level heading is formatted as a first-level heading) or in relationship between source and target (e.g., headings are formatted differently between source and target). However, for calculation purposes, Design issues are generally included with fluency issues for purposes of calculation.</li>','<li>A document is formatted incorrectly</li>','Design'),('diacritics','spelling','Issues related to the use of diacritics','','<li>The Hungarian word <em>bőven</em> (using o with a double acute (˝)) is spelled as <em>bõven</em>, using a tilde (˜), which is not found in Hungarian.</li>','Diacritics'),('discourse','inconsistency','The discourse structure of the text is inconsistent in a confusing or unclear manner.','','<li>The text has a mixture of imperatives, descriptions of actions, and lists within a single process, making it difficult to follow the intended course of action.</li>','Discourse'),('document-external-link','broken-link','A link or cross reference points to an incorrect or nonexistent location outside of the same document within which it occurs','','<li>A link in an HTML document points to a U.S. government URL that has moved and no longer exists.</li>','Document-external link'),('document-internal-link','broken-link','A link or cross reference points to an incorrect or nonexistent location within the same document within which it occurs.','','<li>An internal link refers to the location “#section5” but there is no anchor “section5” in the document.</li>','Document-internal link'),('duplication','content','Content has been duplicated (e.g., a word or longer portion of text is repeated unintentionally).','','<li>A text reads “The man the man whom she saw…”</li><li>A paragraph appears verbatim twice in a row.</li>','Duplication'),('end-user-suitability','verity','The content is not suitable for use by the end user, excluding problems related to suitability for the target locale.','<li>If the issue relates to the applicability of the content to users in a particular locale, //locale-specific-content// should be used instead.</li><li>End-user suitability generally applies to issues present in the source text, regardless of the target locale, but may apply in cases where there are distinct differences in audience or purpose between source and target.</li>','<li>A text describes a process to repair a device, but following the instructions leads to serious damage to the device and potential injury.</li><li>A text assumes that the reader has knowledge of advanced particular physics, but the target audience does not generally have this knowledge.</li>','End-user suitability'),('entity','mistranslation','Names, places, or other “named entities” do not match','','<li>The source text refers to Dublin, Ohio, but the target incorrectly refers to Dublin, Ireland.</li>','Entity (such as name or place)'),('false-friend','mistranslation','The translation has incorrectly used a word that is superficially similar to the source word.','','<li>The Italian word <em>simpatico</em> has been translated as <em>sympathetic</em> in English.</li>','False friend'),('fluency',NULL,'Issues related to the form or content of a text, irrespective as to whether it is a translation or not.','<li>If an issue can be detected <em>only</em> by comparing the source and target, it MUST NOT be categorized as //fluency// or any of its children.</li>','<li>A text has errors in it that prevent it from being understood.</li>','Fluency'),('font','local-formatting','Issues related to local font usage (i.e., font choices that impact a span of content rather than the global choice of the document).','','<li>Warning texts are set in sans-serif, but one of them appears in a serif font.</li><li>A portion of Japanese text is set with an obliqued face (corresponding to italics in the source text) when dot accents should have been used with a non-oblique face.</li>','Font'),('footnote-format','overall-design','Footnotes or endnotes are placed inappropriately or use incorrect in-text symbols','','<li>Specifications state that endnotes should be used with roman numerals but footnotes were used with in-text symbols (*, †, ‡, etc.).</li>','Footnote/endnote format'),('function-words','grammar','A function word (e.g., a preposition, “helping verb”, article, determiner) is used incorrectly.','','<li>A text reads “Check the part number as given in the screen” instead of “…on the screen”.</li><li>A text reads “The graphic is then copied into an internal memory” instead of “The graphic is copied to internal memory.”</li>','Function words'),('global-font-choice','overall-design','The overall font chosen is incorrect or inappropriate.','<li>While this issue may apply to both source and target, it is most likely to apply to the target.</li>','<li>A English source text uses a normal-weight serif font for body text but the Japanese translation uses a heavy-weight “gothic” (roughly, sans-serif) font appropriate for headlines only.</li>','Global font choice'),('grammar','mechanical','Issues related to the grammar or syntax of the text, other than spelling and orthography.','','<li>An English text reads “The man was seeing <em>the his wife</em>.”</li>','Grammar'),('graphics-tables','design','Issues related to the formatting of graphics and tables.','','<li>A graphic is garbled and the wrong version is shown</li>','Graphics and tables'),('graphics-tables-missing','graphics-tables','A graphic or table is missing.','','<li>An HTML file is missing an &lt;img> tag, so no graphic is shown.</li>','Missing graphic/table'),('graphics-tables-position','graphics-tables','A graphic or table is positioned incorrectly.','','<li>A text refers to Figure 1, but Figure 1 appears six pages after the point where it was referred to.</li>','Position of graphic/table'),('headers-footers','overall-design','Headers or footers are formatted incorrectly','','<li>Headers should appear on every page but have been omitted on odd-numbered pages.</li>','Headers and footers'),('images-vs-text','inconsistency','Phrasing/wording is inconsistent between text shown in images and running text.','','<li>A screen shot shows a button with the text “Open other…” but the text referring to the screen shot tells the user to click on the “Open alternative…” button.</li>','Images vs. text'),('improper-exact-match','mistranslation','An translation is provided as an exact match from a TM system, but is actually incorrect.','This issue type applies <em>only</em> in cases where TM technology is used.','<li>A TM system returns “Press the Start button” as an exact (100%) match, when the proper translation should be “Press the Begin button”.</li>','Improper exact match'),('incomplete-list','completeness','A list is missing necessary items','','<li>A list of items included in a retail package omits a crucial component.</li>','Incomplete List'),('incomplete-procedure','completeness','A procedure is missing necessary steps.','<li>In cases where content is missing from the target text that is present in the source text, //omission// should be used instead</li>','<li>A document describing a procedure to restart a diesel generator omits a crucial step that must be completed prior to performing additional steps.</li>','Incomplete procedure'),('inconsistency','content','The text shows internal inconsistency.','','<li>The text states that bug reports should be submitted to a mailing list in one place and via an online bug tracker tool in another.</li>','Inconsistency'),('inconsistent-abbreviations','inconsistency','The form of abbreviations is inconsistent in the text.','','<li>A text uses both “app.” and “approx.” for approximately.</li>','Inconsistent abbreviations'),('inconsistent-link','inconsistency','Links are inconsistent in the text','','<li>An HTML file contains numerous links to other HTML files; some have been updated to reflect the appropriate language version while some point to the source language version.</li>','Inconsistent link/cross-reference'),('inconsistent-markup','markup','Markup elements are inconsistent between the source and target','','<li>A target text has a set of tags for bold face in the same location where the source has tags for italics.</li>','Inconsistent markup'),('index-toc','mechanical','Issues related to an index or Table of Contents (TOC).','','<li>A Table of Contents is missing items that should be included.</li>','Index/TOC'),('index-toc-format','index-toc','An index/TOC is formatted incorrectly','','<li>A Table of Content should be formatted with variable (hierarchical) indenting and tab leader characters, but is instead displayed as a “run-in” list.</li>','Index/TOC format'),('internationalization',NULL,'There is a problem related to the internationalization of content.','<li>While //internationaliztion// errors are generally detected in the target content, they refer to problems in the source that cause problems with translated/localized content. Even in cases where //internationalization// is not being specifically checked, if problems related to internationalization are encountered, they should generally be reported to the content creators.</li><li>As of August 2014, the intention is to expand this branch in the future with more specific issue types.</li>','<li>A document assumes that all addresses use postal codes conforming to the U.S. “zip+four” convention and includes a verification step for postal codes that does not allow for non-U.S. codes.</li><li>A computer program is localized but some content remains untranslated because it was embedded in the program code and not made accessible to the translator.</li>','Internationalization'),('kerning','local-formatting','Kerning (inter-character spacing) is wrong.','','<li>The letters T and A in the word TAMPA are spaced too close together and collide.</li>','Kerning'),('leading','local-formatting','Leading (spacing between lines of text) is off','','<li>A translated Japanese text has set lines too close together, making the text difficult to read.</li>','Leading'),('legal-requirements','verity','A text does not meet legal requirements as set forth in the specifications.','<li>Generally used in cases where the translation does not meet requirements. Cases in which the source text does not meet legal requirements are generally critical errors that will require rewriting the source text.</li>','<li>Specifications stated that FCC regulatory notices be replaced by CE notices rather than translated, but they were translated instead, rendering the text legally problematic for use in Europe.</li>','Legal requirements'),('length','design','There is a significant discrepancy between the source and the target text lengths.','','<li>An English sentence is 253 characters long but its German translation is 51 characters long.</li>','Length'),('local-formatting','design','Issues related to local formatting (rather than to overall layout concerns)','','A portion of the text displays a (non-systematic) formatting problem (e.g., a single heading is formatted incorrectly, even though other headings appear properly).','Local formatting'),('locale-convention','mechanical','The text does not adhere to locale-specific mechanical conventions and violates requirements for the presentation of content in the target locale.','<li>This issue type is distinguished from //locale-specific-content// in that this category refers only to whether the text is given the proper mechanical form for the locale, not whether the content applies to the locale or not. If text conforms to conventions for the locale, but does not apply to the target locale, //locale-specific-content// should be used instead.<li>','<li>An incorrect format for currency is used for a German text, with a period (.) instead of a comma (,) as a thousands separator.</li><li>A text translated into Japanese uses Western quote marks to indicate titles rather than the appropriate Japanese quote marks (「 and 」). (Note: this example would be categorized as //quote-mark-type// if the metric includes it.)</li>','Locale convention'),('locale-specific-content','verity','Content specific to the source locale does not apply to the intended target locale, audience, or purpose.','<li>This issue type is distinguished from //locale-convention// in that this category applies to cases where text corresponds to the conventions of the target locale, but does not <em>apply</em> to the intended audience in the target locale. For example, if the Swedish advertising text mentioned above is properly translated and follows all mechanical locale conventions (e.g., using Swedish kronor instead of euros) but the offer does not apply to Sweden, //cocale-specific-content// should be chosen. If, however, the text applies to the locale, but does not follow locale conventions (e.g., numbers are formatted incorrectly for the locale), //locale-convention// should be used instead.</li>','<li>An advertising text translated for Sweden refers to special offers available only in Germany and therefore is misleading.</li><li>A manual for a printer sold in Spain describes features that apply only to versions of the printer sold in Japan and thus may confuse purchasers.</li>','Locale-specific content'),('margins','overall-design','Text margins are incorrect.','','<li>Specifications called for 4 cm inside margins, but 2.5 cm margins were used instead.</li>','Margins'),('markup','design','Issues related to “markup” (codes used to represent structure or formatting of text, also known as “tags”).','','<li>Markup is used incorrectly, resulting in incorrect formatting.</li>','Markup'),('measurement-format','locale-convention','A text uses a measurement format inappropriate for its locale.','','<li>A text in France uses feet and inches and Fahrenheit temperatures.</li>','Measurement format'),('mechanical','fluency','Issues related to the presentation and/or mechanics of the text','<li>In most assessment instances, use of more specific children would be appropriate.</li>','<li>While the informational content of a text is correct, it is presented in a mechanically defective fashion.</li>','Mechanical'),('misplaced-markup','markup','Markup is present but misplaced.','','<li>A segment has three sets of paired formatting tags at the end, after the final full stop (.).</li>','Misplaced markup'),('missing-incorrect-toc-item','index-toc','Items in an index/TOC are incorrect or missing','','<li>A chapter heading is not listed in a Table of Contents.</li>','Missing/incorrect TOC item'),('missing-markup','markup','Markup in the source is missing in the target.','','<li>A source segment has a set of italic tags, but the target text does not have any tags.</li>','Missing markup'),('mistranslation','accuracy','The target content does not accurately represent the source content.','','<li>A source text states that a medicine should not be administered in doses greater than 200 mg, but the translation states that it should be administered in doses greater than 200 mg (i.e., negation has been omitted).</li>','Mistranslation'),('monolingual-terminology','content','Terms (as opposed to general-language words) are used incorrectly.','<li>Generally applies to the source only. If in doubt, //terminology// would be used in most cases for the target text.</li><li>This category differs from //terminology// in that it applies in cases where the problem is specific to one text and is not the result of the mistranslation of terms.</li>','<li>The term <em>piano action</em> should be used but <em>piano mechanism</em> is used instead.</li>','Monolingual terminology'),('national-language-standard','locale-convention','A text violates national language standards.','','<li>A French advertising text uses anglicisms that are forbidden for print texts by the Academie française specifications.</li>','National language standard'),('no-translate','mistranslation','Text was translated that should have been left untranslated','','<li>A Japanese translation refers to “Apple Computers” as アップルコンピュータ when the English expression should have been left untranslated.</li>','Should not have been translated'),('nonallowed-characters','mechanical','The text includes characters that are not allowed.','','<li>A text may not include colons or forward- or back-slashes, which might cause confusion with path names on some computer systems, but it contains these characters.</li>','Nonallowed characters'),('normative-monolingual-terminology','monolingual-terminology','Terms are used in violation of formal guidelines in a terminology database or other terminology resource.','','<li>As with //monolingual-terminology//, generally applies to source only.</li><li>A text uses the term “Acme TM200” instead of the mandated “Acme TM2000®”.</li>','Normative monolingual terminology'),('number','mistranslation','Numbers are inconsistent between source and target.','','<li>The source text specifies that a part is 124 mm long but the target text specifies that it is 135 mm long.</li>','Number'),('number-format','locale-convention','A text uses a number format inappropriate for its locale.','','<li>A German text has “123,456” instead of the locale-appropriate “123.456”.</li>','Number format'),('omission','accuracy','Content is missing from the translation that is present in the source.','','<li>A paragraph present in the source is missing in the translation</li>','Omission'),('omitted-variable','omission','A variable placeholder is omitted from a translated text','','<li>A translated text should read “Number of lives remaining: $lifeNumber” but is rendered as “Number of lives remaining:”, with the variable <code>$lifeNumber</code> omitted</li>','Omitted variable'),('other',NULL,'Used for any issues not adequately covered by the MQM core or extensions. This category should be used only if it is impossible to assign an issue to an existing category with sufficient granularity.','<li>This category should be used only for any issue type that cannot be mapped to one of the issue types listed above. If an issue type can be considered a more granular example of an existing type, it should be categorized as that type, possibly with a custom extension if the additional granularity is needed.</li>','<li>A quality process checks for errors generated from speech-to-text generated during conference interpretation. Because this error type is highly specific to the specific situation, it is not included in any predefined issue type elsewhere.</li>','Other'),('overall-design','design','Issues related to overall layout and design (versus local formatting)','','<li>A document is formatted incorrectly (e.g., it should have been set up for a print layout but instead is set up for an online presentation.</li>','Overall design (layout)'),('overly-literal','mistranslation','The translation is overly literal.','','<li>A Hungarian text contains the phrase <em>Tele van a hocipőd?</em>, which has been translated as “Are your snow boots full?” rather than with the idiomatic meaning of “Feeling overwhelmed?”.</li>','Overly literal'),('page-breaks','overall-design','Page breaks appear in inappropriate locations.','','<li>There is a page break between a figure and its caption.</li>','Page breaks'),('page-references','index-toc','An index/TOC refers to incorrect page numbers','','<li>A table of contents refers to page numbers from the source document that do not apply to the translated text.</li>','Page references'),('paragraph-indentation','local-formatting','A paragraph is indented improperly.','','<li>The first line of body paragraphs should be indented 4 mm, but some paragraphs were indented 25 mm instead.</li>','Paragraph indentation'),('part-of-speech','word-form','A word is the wrong part of speech','','<li>A text reads “Read these instructions careful” instead of “Read these instructions carefully.”</li>','Part of speech'),('pattern-problem','mechanical','The text contains a pattern (e.g., text that matches a regular expression) that is not allowed.','','<li>The regular expression <code>[\"\'”’][,\\.;]</code> (i.e., a quote mark followed by a comma, full stop, or semicolon) is defined as not allowed for a project but a text contains the string <code>”,</code> (closing quote followed by a comma).</li>','Pattern problem'),('punctuation','typography','Punctuation is used incorrectly (for the locale or style)','<li>In most cases it is not necessary to distinguish this issue type from //typography//.</li>','<li>An English text uses a semicolon where a comma should be used.</li>','Punctuation'),('questionable-markup','markup','Markup is present that appears malformed or inappropriate for its context.','','<li>A text has opening tags but no closing tags for formatting.</li>','Questionable markup'),('quote-mark-type','locale-convention','A text uses quote marks inappropriate for its locale.','','<li>A French text should use guillemets («») but instead systematically uses German-style quotes („”)</li>','Quote mark type'),('register','content','The text uses a linguistic register inconsistent with the specifications or general language conventions.','','<li>A legal notice in German uses the informal <em>du</em> instead of the formal <em>Sie</em>.</li>','Register'),('single-double-width','font','Single-width characters are used when double-width are intended, or vice versa.','','<li>A Japanese text includes カタカナ (full-width kana) when specifications required ｶﾀｶﾅ (half-width kana) instead, due to a limited display size.</li>','Font, single/double-width (CJK only)'),('sorting','mechanical','A list is not in the appropriately collated sequence.','','<li>A listing of items should be in alphabetical order but appears in a random order instead.</li>','Sorting'),('spelling','mechanical','Issues related to spelling of words','','<li>The German word <em>Zustellung</em> is spelled <em>Zustetlugn</em>.</li>','Spelling'),('style-guide','mechanical','The text violates style defined in a normative style specification.','','<li>Specifications stated that English text was to be formatted according to the Chicago Manual of Style, but the text delivered followed the American Psychological Association style guide.</li>','Style guide'),('stylistics','content','The text has stylistic problems, other than those related to language register.','','<li>A text uses a confusing style with long sentences that are difficult to understand.</li>','Stylistics'),('tense-mood-aspect','word-form','A verbal form displays the wrong tense, mood, or aspect','','An English text reads “After the button is pushing” (present progressive) instead of “After the button has been pushed” (past passive)','Tense/mood/aspect'),('term-inconsistency','inconsistency','Terminology is used in an inconsistent manner within the text.','This issue should not be used to cases where terminology has been translated incorrectly (//terminology//) or cases where the wrong term is used in a source document (//monolingual-terminology//).','<li>The text refers to a component as the brake release lever, brake disengagement lever, manual brake release, and manual disengagement.</li>','Terminological inconsistency'),('terminology','mistranslation','A term (domain-specific word) is translated with a term other than the one expected for the domain or otherwise specified.','','<li>A French text translates English <em>e-mail</em> as <em>e-mail</em> but terminology guidelines mandated that <em>courriel</em> be used.</li><li>The English musicological term <em>dog</em> is translated (literally) into German as <em>Hund</em> instead of as <em>Schnarre</em>, as specified in a terminology database.</li>','Terminology'),('terminology-company','terminology','The text violates company/organization-specific terminology guidelines.','Should be used only when it is necessary to distinguish company-specific terminology issues from more general (domain) terminology issues.','<li>Company-specific terminology guidelines specify that a product be called the “Acme Turbo2000™”, but the text calls it the “Acme Turbo” or the “Turbo200”.</li>','Company terminology'),('terminology-normative','terminology','A term is translated in a way that does not accord with its normative translation (i.e., a translation mandated in an authoritative listing of terms and their translations that was specified for use in the translation) versus general-domain usage.','<li>Unless normative terminology references are used, generally //terminology// should be used instead.</li>','<li>A database of internationally standardized legal terms mandates that the English term contract be translated as <em>Auftrag</em> in German, but the more common <em>Vertrag</em> was used.</li>','Normative terminology'),('text-alignment','local-formatting','A portion of a text is aligned inappropriately.','','<li>A heading should be left-aligned but was centered instead.</li>','Text alignment'),('time-format','locale-convention','A text uses a time format inappropriate for its locale.','','<li>A text written for the U.S. uses a 24-hour time notation rather than AM/PM time.</li>','Time format'),('truncation-text-expansion','design','The target text has insufficient room to display the translated text according to specifications.','<li>This issue may indicate an underlying //internationalization// problem.</li>','<li>The German translation of an English string in a user interface runs off the edge of a dialogue box and cannot be read.</li>','Truncation/text expansion'),('typography','mechanical','Issues related to the mechanical presentation of text. This category should be used for any typographical errors other than spelling.','<li>Do not use for issues related to //spelling//.</li>','<li>A text uses punctuation incorrectly.</li><li>A text has an extraneous hard return in the middle of a paragraph.</li>','Typography'),('unclear-reference','ambiguity','The text uses relative pronouns or other referential mechanisms that are unclear as to their reference.','','<li>A text reads “After completing this, move to the next step,” but there are a number of possible referents for <em>this</em> in the text.</li>','Unclear reference'),('unidiomatic',NULL,'The content is grammatical, but not idiomatic','','<li>The following text appears in an English translation of a German letter: “We thanked him with heart” where “with heart” is an understandable, but non-idiomatic rendering, better stated as “heartily”.</li>','Unidiomatic'),('unintelligible','fluency','The exact nature of the error cannot be determined. Indicates a major break down in fluency.','','<li>The following text appears in an English translation of a German automotive manual: “The brake from whe this કુતારો િસ S149235 part numbr,,.”</li><li>Text appears in a translation that cannot be understood at all.</li>','Unintelligible'),('unit-conversion','mistranslation','The target text has not converted numeric values as needed to adjust for different units (e.g., currencies, metric vs. U.S. measurement systems).','','<li>A source text specifies that an item is 25 centimeters (~10 inches) long, but the source states that it is 25 inches (63.5 cm) long.</li>','Unit conversion'),('unpaired-marks','typography','One of a pair of quotes or brackets—e.g., a (, ) [, ], {, or } character—is missing from text.','','<li>A text reads “King Ludwig of Bavaria (1845–1896 was deposed on account of his supposed madness,” omitting the closing parenthesis around the dates.</li>','Unpaired quote marks or brackets'),('untranslated','accuracy','Content that should have been translated has been left untranslated.','','<li>A sentence in a Japanese document translated into English is left in Japanese.</li>','Untranslated'),('untranslated-graphic','untranslated','Text in a graphic was left untranslated.','','<li>Part labels in a graphic were left untranslated even though running text was translated</li>','Untranslated graphic'),('variants-slang','register','The text uses words such as slang that are inappropriate for the intended register.','','<li>A refers to dollars as “clams” in a case when this slang term would be inappropriate.</li>','Variants/slang'),('verity',NULL,'The text makes statements that contradict the world of the text','<li>Verity issues can apply to the source or target text and often emerge during translation when, for example, a factual statement is true in the source locale but not true in the target locale.</li>','<li>The text states that a feature is present on a certain model of automobile when in fact it is not available.</li>','Verity'),('whitespace','design','Whitespace is used incorrectly','','<li>A document uses a string of space characters instead of tabs</li><li>Extra spaces are added at the start of a string</li>','Whitespace'),('widows-orphans','overall-design','The text has widows or orphans (single or short lines of text that appear on a separate page from the rest of a paragraph).','','<li>Specifications state that at least two lines of a paragraph must appear on a page (if the paragraph is more than one line), but a single line starts a page while two appear on the previous page.</li>','Widows/orphans'),('word-form','grammar','There is a problem in the form of a word','','<li>An English text has <em>becomed</em> instead of <em>became</em>.</li>','Word form'),('word-order','grammar','The word order is incorrect','','<li>A German text reads “Er hat gesehen den Mann” instead of “Er hat den Mann gesehen.”</li>','Word order'),('wrong-font-size','font','The font size is incorrect','','<li>A legal notice should be set in a 9 pt size, but was instead set in 7 pt.</li>','Wrong size');
/*!40000 ALTER TABLE `issue` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-04-10 14:46:59