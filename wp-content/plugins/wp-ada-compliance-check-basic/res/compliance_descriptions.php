<?php 
/*
Plugin - WP ADA Compliance Check
define all compliance check messages
*/

// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;

/********************************/
// theme files missing skip links
/********************************/
$wp_ada_compliance_basic_def['skip_nav_links']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! A Skip link was not found. Skip links are required to allow screen reader users to bypass navigation links and go directly to the main content. The skip link target id should be the id for the main landmark on the page and should include the H1 for the page. A skip link should be the first link in a page and are normally hidden from view but should become visible when in focus. Edit theme files to include skip navigation links or upgrade to the full version to auto correct this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['skip_nav_links']['StoredError'] = __('A Skip link was not found. Skip links are required to allow screen reader users to bypass navigation links and go directly to the main content. The skip link target id should be the id for the main landmark on the page and should include the H1 for the page. A skip link should be the first link in a page and are normally hidden from view but should become visible when in focus. Edit theme files to include skip navigation links or upgrade to the full version to auto correct this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['skip_nav_links']['Settings'] = __('A Skip link was not found.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['skip_nav_links']['Reference'] = __('WCAG 2.1 (Level A) - 2.4.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['skip_nav_links']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#bypass-blocks';
$wp_ada_compliance_basic_def['skip_nav_links']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/G1.html';
$wp_ada_compliance_basic_def['skip_nav_links']['HelpINSTR'] = __('Edit theme files to include skip navigation links or upgrade to the full version to auto correct this issue.','wp-ada-compliance-basic');

/********************************/
// missing or incorrect aria landmarks
/********************************/
$wp_ada_compliance_basic_def['missing_landmarks']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! This page is missing one or more required landmark regions. Landmark regions programmatically identify sections of a page and allow assistive technology users to navigate to various sections of the page. Not all landmark types will be applicable to every page and it is not necessary to add landmarks if they are not applicable to a page but normally each page will have at least one banner, navigation, main and contentinfo region. Other regions that may be applicable include complementary, form and search. If more than one landmark of the same type exists the aria-label attribute should be used to describe its purpose. Landmarks may be added using either html5 elements or the role attribute. Review the page structure and add any applicable landmarks. Upgrade to the full version to auto correct this issue and to add checks for incorrectly labeled landmarks.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_landmarks']['StoredError'] = __('This page is missing one or more required landmark regions. Landmark regions programmatically identify sections of a page and allow assistive technology users to navigate to various sections of the page. Not all landmark types will be applicable to every page and it is not necessary to add landmarks if they are not applicable to a page but normally each page will have at least one banner, navigation, main and contentinfo region. Other regions that may be applicable include complementary, form and search. If more than one landmark of the same type exists the aria-label attribute should be used to describe its purpose. Landmarks may be added using either html5 elements or the role attribute. Review the page structure and add any applicable landmarks. Upgrade to the full version to auto correct this issue and to add checks for incorrectly labeled landmarks.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_landmarks']['Settings'] = __('Missing landmark regions.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_landmarks']['Reference'] = __('WCAG 2.1 (Level A) - 1.3.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['missing_landmarks']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#info-and-relationships';
$wp_ada_compliance_basic_def['missing_landmarks']['HelpURL'] = 'https://www.w3.org/TR/wai-aria-practices/examples/landmarks/main.html';
$wp_ada_compliance_basic_def['missing_landmarks']['HelpINSTR'] = __('Review the page structure and add any applicable landmarks. Upgrade to the full version to auto correct this issue and to add checks for incorrectly labeled landmarks.','wp-ada-compliance-basic');

/********************************/
// unlabeled landmarks
/********************************/
$wp_ada_compliance_basic_def['unlabeled_landmarks']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more landmarks were found without proper labeling. Form landmarks should be identified using the aria-labelledby attribute set to the id of a visible heading element (e.g. an h1-h6 element) or if more than one landmark of the same type exists the aria-label attribute should be used to describe the purpose of each landmark. Correctly label any applicable landmarks. The autocorrect feature found on the content filters tab may also be used to add labels to landmarks.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['unlabeled_landmarks']['StoredError'] = __('One or more landmarks were found without proper labeling. Form landmarks should be identified using the aria-labelledby attribute set to the id of a visible heading element (e.g. an h1-h6 element) or if more than one landmark of the same type exists the aria-label attribute should be used to describe the purpose of each landmark. Refer to the affected code for more details. Correctly label any applicable landmarks. The autocorrect feature found on the content filters tab may also be used to add labels to landmarks. ','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['unlabeled_landmarks']['Settings'] = __('Improperly labeled landmark regions.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['unlabeled_landmarks']['Reference'] = __('WCAG 2.1 (Level A) - 1.3.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['unlabeled_landmarks']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#info-and-relationships';
$wp_ada_compliance_basic_def['unlabeled_landmarks']['HelpURL'] = 'https://www.w3.org/TR/wai-aria-practices/examples/landmarks/main.html';
$wp_ada_compliance_basic_def['unlabeled_landmarks']['HelpINSTR'] = __('Label any applicable landmarks. The autocorrect feature found on the content filters tab may also be used add labels to landmarks.','wp-ada-compliance-basic');

/********************************/
// theme files with meta refresh attributes that reload the page or redirect to a new location after a timeout
/********************************/
$wp_ada_compliance_basic_def['meta_refresh_use']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more meta elements were found to be using a refresh attribute to refresh or redirect after a time limit. People who are blind may not have enough time for their screen readers to read the page before the page refreshes or redirects. Sighted users may also be disoriented by the unexpected refresh. You should remove this element or provide a mechanism for users to stop the refresh. If a mechanism has already been provided this issue may be ignored.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['meta_refresh_use']['StoredError'] = __('A meta element was found to be using a refresh attribute to refresh or redirect after a time limit. People who are blind may not have enough time for their screen readers to read the page before the page refreshes or redirects.  Sighted users may also be disoriented by the unexpected refresh. You should remove this element or provide a mechanism for users to stop the refresh. If a mechanism has already been provided this issue may be ignored.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['meta_refresh_use']['Settings'] = __('Meta element used to refresh or redirect the page after a time limit.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['meta_refresh_use']['Reference'] = __('WCAG 2.1 (Level A) - 2.2.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['meta_refresh_use']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#timing-adjustable';
$wp_ada_compliance_basic_def['meta_refresh_use']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/F40.html';
$wp_ada_compliance_basic_def['meta_refresh_use']['HelpINSTR'] = __('Remove this element or provide a mechanism for users to stop the refresh.','wp-ada-compliance-basic');


/********************************/
// theme files missing language attribute
/********************************/
$wp_ada_compliance_basic_def['missing_lang_attr']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! HTML elements where found to be missing the language attribute. HTML tags are normally found in theme files. Without the language attribute the language of each Web page can be programmatically determined. Edit theme files to include a language attribute on the html element. e.g. &lt;html lang="en-US"&gt;','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_lang_attr']['StoredError'] = __('HTML element is missing the language attribute. HTML tags are normally found in theme files. Without the language attribute the language of each Web page can be programmatically determined. Edit theme files to include a language attribute on the html element. e.g. &lt;html lang="en-US"&gt;','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_lang_attr']['Settings'] = __('Language attribute not included on html elements.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_lang_attr']['Reference'] = __('WCAG 2.1 (Level A) - 3.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['missing_lang_attr']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#language-of-page';
$wp_ada_compliance_basic_def['missing_lang_attr']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/H57.html';
$wp_ada_compliance_basic_def['missing_lang_attr']['HelpINSTR'] = __('Edit theme files to include language attribute on the html element. e.g. &lt;html lang="en-US"&gt;','wp-ada-compliance-basic');

/********************************/
// av tag with autoplay
/********************************/
$wp_ada_compliance_basic_def['av_tag_with_autoplay']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more audio or video elements were found to be set to autoplay. If any audio plays automatically for more than 3 seconds or video includes motion that lasts for more than 5 seconds a mechanism must be available to pause or stop the media. Switch to text view, locate the affected code and remove the autoplay attribute. If the affected code is an Iframe with an MP3 or MP4 source the Iframe should be replaced with an audio of video tag.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['av_tag_with_autoplay']['StoredError'] = __('An audio or video element is set to auto play. If any audio plays automatically for more than 3 seconds or video includes motion that lasts for more than 5 seconds a mechanism must be available to pause or stop the media. Switch to text view, locate the affected code and remove the autoplay attribute. If the affected code is an Iframe with an MP3 or MP4 source the Iframe should be replaced with an audio of video tag.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['av_tag_with_autoplay']['Settings'] = __('Audio or video elements set to auto play but that do not include an option to stop play.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['av_tag_with_autoplay']['Reference'] = __('WCAG 2.1 (Level A) - 1.4.2, 2.2.2','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['av_tag_with_autoplay']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#audio-control';
$wp_ada_compliance_basic_def['av_tag_with_autoplay']['HelpURL'] = 'https://www.w3.org/TR/UNDERSTANDING-WCAG20/visual-audio-contrast-dis-audio.html';
$wp_ada_compliance_basic_def['av_tag_with_autoplay']['HelpINSTR'] = __('Switch to text view, locate the affected code and remove the autoplay attribute. For example:
&lt;video src="ads.cgi?kind=video" &gt;&lt;/video&gt; If the affected code is an Iframe with an MP3 or MP4 source the Iframe should be replaced with an audio of video tag.','wp-ada-compliance-basic');

/********************************/
// image map hot spot missing alt text
/********************************/
$wp_ada_compliance_basic_def['imagemap_missing_alt_text']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more image map area tags were found to be missing alternate text. Each area tag inside an image map must include an alt attribute with text describing the purpose of the linked area. Switch to text view, locate the affected code and insert an alt attribute for each area tag.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['imagemap_missing_alt_text']['StoredError'] = __('An image map area tag was found to be missing alternate text. Each area tag inside an image map must include an alt attribute with text describing the purpose of the linked area. Switch to text view, locate the affected code and insert an alt attribute for each area tag.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['imagemap_missing_alt_text']['Settings'] = __('Image map area tags missing alternate text.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['imagemap_missing_alt_text']['Reference'] = __('WCAG 2.1 (Level A) - 1.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['imagemap_missing_alt_text']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#non-text-content';
$wp_ada_compliance_basic_def['imagemap_missing_alt_text']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/H24.html';
$wp_ada_compliance_basic_def['imagemap_missing_alt_text']['HelpINSTR'] = __('Switch to text view, locate the affected code and insert an alt attribute for each area tag. For example:
&lt;map name="planetmap"&gt;
  &lt;area shape="rect" coords="0,0,82,126" href="sun.htm" alt="Sun"&gt;
  &lt;area shape="circle" coords="90,58,3" href="mercur.htm" alt="Mercury"&gt;
  &lt;area shape="circle" coords="124,58,8" href="venus.htm" alt="Venus"&gt;
&lt;/map&gt;
','wp-ada-compliance-basic');

/********************************/
// redundant link text
/********************************/
$ada_compliance_dynamic_text =  __(" Ensure this link is included in a paragraph, list or table cell with preceding text that describes its purpose or is in a table cell with a header that explains the purpose of the link. To increase accessibility consider changing the link text to distinguish the links from one another or switch to Text view and add a title attribute with supplemental text. The aria-label attribute may also be used and will replace the link text for screen readers.","wp-ada-compliance-basic");	
$wp_ada_compliance_basic_def['redundant_anchor_text']['DisplayError'] = __('<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ALERT!  One or more adjacent anchor tags were found with the same link text but with different destinations.','wp-ada-compliance-basic').$ada_compliance_dynamic_text;

$wp_ada_compliance_basic_def['redundant_anchor_text']['StoredError'] = __(' One or more adjacent anchor tags were found with the same link text but with different destinations.','wp-ada-compliance-basic').$ada_compliance_dynamic_text;

$wp_ada_compliance_basic_def['redundant_anchor_text']['Settings'] = __('Adjacent anchor tags with the same link text but with different destinations.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['redundant_anchor_text']['Reference'] = __('WCAG 2.1 (Level AA) - 2.4.4','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['redundant_anchor_text']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#link-purpose-in-context';
$wp_ada_compliance_basic_def['redundant_anchor_text']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/H33.html';
$wp_ada_compliance_basic_def['redundant_anchor_text']['HelpINSTR'] = $ada_compliance_dynamic_text;

/********************************/
// redundant title text
/********************************/
$wp_ada_compliance_basic_def['redundant_title_tag']['DisplayError'] = __('<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ALERT! One or more anchor tags include a title attribute that duplicates the content within the body of the link, an aria-label or the alt text on an image. This creates redundancy, therefore the title attribute should be removed. Switch to Text view and remove the title attribute from this link.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['redundant_title_tag']['StoredError'] = __('An anchor tag includes a title attribute that duplicates the content within the body of the link, an aria-label or the alt text on an image. This creates redundancy, therefore the title attribute should be removed. Switch to Text view and remove the title attribute from this link.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['redundant_title_tag']['Settings'] = __('Anchor tags with title attribute that duplicates the content within the body of the link, an aria-label or the alt text on an image.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['redundant_title_tag']['Reference'] = __('WCAG 2.1 (Level AA) - 2.4.4','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['redundant_title_tag']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#link-purpose-in-context';
$wp_ada_compliance_basic_def['redundant_title_tag']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/H33.html';
$wp_ada_compliance_basic_def['redundant_title_tag']['HelpINSTR'] = __('Switch to Text view and remove the title attribute from this link.','wp-ada-compliance-basic');;


/********************************/
// images with invalid alternate text
/********************************/
$wp_ada_compliance_basic_def['img_alt_invalid']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more images were found with invalid alternate text. This could mean that it includes a filename, filler content, words or phrases that would have no meaning to a screen reader user. Without valid alternative text, the content of an image will not be available to screen reader users or when the image is unavailable. Each image that conveys meaning must have a valid alt attribute unless including the alternate text would create redundancy (e.g. when text is also included in an image caption or text inside the body of a link) or the image is included for decoration. Use the edit image option to add alt text to the image and remove any unnecessary or invalid title attributes. If the image includes a phrase such as "image of" or "photo of", this is unnecessary since it is apparent to the user that it is an image. If the fact that an image is a photograph or illustration is important, it may be useful to include it in the alternative text and this error can be ignored.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_alt_invalid']['StoredError'] = __('An image was found with invalid alternate text. This could mean that it includes a filename, filler content, words or phrases that would have no meaning to a screen reader user. Without valid alternative text, the content of an image will not be available to screen reader users or when the image is unavailable. Each image that conveys meaning must have a valid alt attribute unless including the alternate text would create redundancy (e.g. when text is also included in an image caption or text inside the body of a link) or the image is included for decoration. Use the edit image option to add alt text to the image and remove any unnecessary or invalid title attributes. If the image includes a phrase such as "image of" or "photo of", this is unnecessary since it is apparent to the user that it is an image. If the fact that an image is a photograph or illustration is important, it may be useful to include it in the alternative text and this error can be ignored.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_alt_invalid']['Settings'] = __('Image with alternate text that includes a filename, filler content, words or phrases that would have no meaning to a screen reader user.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_alt_invalid']['Reference'] = __('WCAG 2.1 (Level A) - 1.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['img_alt_invalid']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#non-text-content';
$wp_ada_compliance_basic_def['img_alt_invalid']['HelpURL'] = 'https://www.w3.org/WAI/WCAG21/Techniques/html/H37.html';
$wp_ada_compliance_basic_def['img_alt_invalid']['HelpINSTR'] = __('Use the edit image option to change the alt text.','wp-ada-compliance-basic');

/********************************/
// redundant alt text
/********************************/
$wp_ada_compliance_basic_def['redundant_alt_text']['DisplayError'] = __('<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ALERT! One or more images were found with alternate text that is the same as the text provided in the image caption, title attribute, body of a link or surrounding content. This creates redundancy and should be avoided. Review this image to deteremine if the alternate text provides additional, useful information for the screen reader user, if it does not, remove any title attributes and leave the alt attribute blank.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['redundant_alt_text']['StoredError'] = __('An image was found with alternate text that is the same as the text provided in the image caption, title attribute, body of a link or surrounding content. This creates redundancy and should be avoided. Review this image to deteremine if the alternate text provides additional, useful information for the screen reader user, if it does not, remove any title attributes and leave the alt attribute blank.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['redundant_alt_text']['Settings'] = __('Images with alternate text that is the same as the text provided in the image caption, title attribute, body of a link or surrounding content.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['redundant_alt_text']['Reference'] = __('WCAG 2.1 (Level A) - 1.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['redundant_alt_text']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#non-text-content';
$wp_ada_compliance_basic_def['redundant_alt_text']['HelpURL'] = 'https://www.w3.org/WAI/WCAG21/Techniques/html/H37.html';
$wp_ada_compliance_basic_def['redundant_alt_text']['HelpINSTR'] = __('Review this image to deteremine if the alternate text provides additional, useful information for the screen reader user, if it does not, remove any title attributes and leave the alt attribute blank.','wp-ada-compliance-basic');

/********************************/
// duplicate titles
/********************************/
$wp_ada_compliance_basic_def['duplicate_title']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! Page title is used for more than one page or post. The first thing screen readers read when a user goes to a different web page is the page title. Good page titles are important to help people know where they are on a website. To avoid confusion for screen reader users, consider using a different page title.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['duplicate_title']['StoredError'] = __('Page title is used for more than one page or post. The first thing screen readers read when a user goes to a different web page is the page title. Good page titles are important to help people know where they are on a website. To avoid confusion for screen reader users, consider using a different page title.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['duplicate_title']['Settings'] = __('Page title that is used for more than one page or post.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['duplicate_title']['Reference'] = __('WCAG 2.1 (Level A) - 2.4.2','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['duplicate_title']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#page-titled';
$wp_ada_compliance_basic_def['duplicate_title']['HelpURL'] = 'https://www.w3.org/TR/UNDERSTANDING-WCAG20/navigation-mechanisms-title.html';
$wp_ada_compliance_basic_def['duplicate_title']['HelpINSTR'] = __('Page titles are shown in the window title bar in some browsers, shown in browsers tabs when there are multiple web pages open, shown in search engine results, used for browser bookmarks/favorites, and read by screen readers. Check that the title adequately and briefly describes the content of the page. Check that the title is different from other pages on the website, and adequately distinguishes the page from other web pages.','wp-ada-compliance-basic');

/********************************/
// missing titles
/********************************/
$wp_ada_compliance_basic_def['missing_title']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! Page title is blank, missing or invalid. The first thing screen readers read when a user goes to a different web page is the page title. Good page titles are important to help people know where they are on a website. To correct this issues add a title to this page.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_title']['StoredError'] = __('Page title is blank, missing or invalid. The first thing screen readers read when a user goes to a different web page is the page title. Good page titles are important to help people know where they are on a website. To correct this issues add a title to this page.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_title']['Settings'] = __('Page title that is blank, missing or invalid.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_title']['Reference'] = __('WCAG 2.1 (Level A) - 2.4.2','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['missing_title']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#page-titled';
$wp_ada_compliance_basic_def['missing_title']['HelpURL'] = 'https://www.w3.org/TR/UNDERSTANDING-WCAG20/navigation-mechanisms-title.html';
$wp_ada_compliance_basic_def['missing_title']['HelpINSTR'] = __('Page titles are shown in the window title bar in some browsers, shown in browsers tabs when there are multiple web pages open, shown in search engine results, used for browser bookmarks/favorites, and read by screen readers. Check that the title adequately and briefly describes the content of the page. Check that the title is different from other pages on the website, and adequately distinguishes the page from other web pages.','wp-ada-compliance-basic');

/********************************/
// audio or video tags missing track
/********************************/
$wp_ada_compliance_basic_def['av_tags_missing_track']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more audio or video elements may be missing equivalent text. Equivalent text should be provided in the form of a track tag including captions or subtitles or a text transcript may be included instead of using captions or subtitles. If closed captioning is provided, the accuracy of the closed captioning should be verified. If a transcript is provided in some other way or the multimedia includes audio with accurate captions this error may be ignored.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['av_tags_missing_track']['StoredError'] = __('An audio or video element may be missing equivalent text. Equivalent text should be provided in the form of a track tag including captions or subtitles or a text transcript may be included instead of using captions or subtitles. If closed captioning is provided, the accuracy of the closed captioning should be verified. If a transcript is provided in some other way or the multimedia includes audio with accurate captions this error may be ignored.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['av_tags_missing_track']['Settings'] = __('Audio or video elements without equivalent text. ','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['av_tags_missing_track']['Reference'] = __('WCAG 2.1 (Level A) - 1.2.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['av_tags_missing_track']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#audio-only-and-video-only-prerecorded';
$wp_ada_compliance_basic_def['av_tags_missing_track']['HelpURL'] = 'https://www.w3.org/TR/UNDERSTANDING-WCAG20/media-equiv-av-only-alt.html';
$wp_ada_compliance_basic_def['av_tags_missing_track']['HelpINSTR'] = __('The minimum requirement is provide a transcript of the audio or video on the website along with the media or to provide captions for multimedia that includes audio. There are other compliance options such as audio descriptions, captions and subtitles but the process for emplementing these features is to complex to present here. Refer to the "More Help" link for additional instructions.','wp-ada-compliance-basic');

/********************************/
// form fields without labels
/********************************/
$wp_ada_compliance_basic_def['missing_form_label']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more form fields were found with empty or missing labels. Without labels screen reader users may not be able to use the form. Switch to Text view and add label tags with a "for" attribute matching an "id" attribute on each form field. If more than one field requires the same label aria-labelledby may be used instead of a label with the for attribute. The free <a href="https://www.alumnionlineservices.com/free-plugins/simple-accessible-forms/">Simple Accessible Forms Plugin</a> may be used to correct most form accessibility issues.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_form_label']['StoredError'] = __('A form field was found without a label or the label was empty. Without labels screen reader users may not be able to use the form. Switch to Text view and add a label tag with a "for" attribute matching an "id" attribute in the form field. If more than one field requires the same label aria-labelledby may be used instead of a label with the for attribute. The free <a href="https://www.alumnionlineservices.com/free-plugins/simple-accessible-forms/">Simple Accessible Forms Plugin</a> may be used to correct most form accessibility issues.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_form_label']['Settings'] = __('Form fields with empty or missing labels.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_form_label']['Reference'] = __('WCAG 2.1 (Level A) - 3.3.2','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['missing_form_label']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#labels-or-instructions';
$wp_ada_compliance_basic_def['missing_form_label']['HelpURL'] = 'https://www.w3.org/TR/UNDERSTANDING-WCAG20/minimize-error-cues.html';
$wp_ada_compliance_basic_def['missing_form_label']['HelpINSTR'] = __('Switch to Text view and add label tags with a for attribute that matches an id attribute on each form field.<br /><br />  &lt;label for="firstname"&gt; First name:&lt;/label&gt; &lt;input type="text" name="firstname" id="firstname"&gt; <br /><br />If more than one field requires the same label the aria-labelledby attribute may be added to the field instead of a label with the for attribute. For Example: <br /><br />
<table>
	<tr>
		<th id="namelabel" scope="col">Name</th>
		<th id="agelabel" scope="col">Age</th>
		<th id="weightlabel" scope="col">Weight</th>
	</tr>
	<tr>
		<td><input type="text" name="name1" aria-labelledby="namelabel"></td>
		<td><input type="text" size="3" name="age1" aria-labelledby="agelabel"></td>
		<td><input type="text" size="4" name="weight1" aria-labelledby="weightlabel"></td>
	</tr>
	<tr>
		<td><input type="text" name="name2" aria-labelledby="namelabel"></td>
		<td><input type="text" size="3" name="age2" aria-labelledby="agelabel"></td>
		<td><input type="text" size="4" name="weight2" aria-labelledby="weightlabel"></td>
	</tr>
	<tr>
		<td><input type="text" name="name2" aria-labelledby="namelabel"></td>
		<td><input type="text" size="3" name="age2" aria-labelledby="agelabel"></td>
		<td><input type="text" size="4" name="weight2" aria-labelledby="weightlabel"></td>
	</tr>
	</table>
<br />
&lt;table&gt;
	&lt;tr&gt;
		&lt;th id="namelabel" scope="col"&gt;Name&lt;/th&gt;
		&lt;th id="agelabel" scope="col"&gt;Age&lt;/th&gt;
		&lt;th id="weightlabel" scope="col"&gt;Weight&lt;/th&gt;
	&lt;/tr&gt;
	&lt;tr&gt;
		&lt;td&gt;&lt;input type="text" name="name1" aria-labelledby="namelabel"&gt;&lt;/td&gt;
		&lt;td&gt;&lt;input type="text" size="3" name="age1" aria-labelledby="agelabel"&gt;&lt;/td&gt;
		&lt;td&gt;&lt;input type="text" size="4" name="weight1" aria-labelledby="weightlabel"&gt;&lt;/td&gt;
	&lt;/tr&gt;
	&lt;tr&gt;
		&lt;td&gt;&lt;input type="text" name="name2" aria-labelledby="namelabel"&gt;&lt;/td&gt;
		&lt;td&gt;&lt;input type="text" size="3" name="age2" aria-labelledby="agelabel"&gt;&lt;/td&gt;
		&lt;td&gt;&lt;input type="text" size="4" name="weight2" aria-labelledby="weightlabel"&gt;&lt;/td&gt;
	&lt;/tr&gt;
	&lt;tr&gt;
		&lt;td&gt;&lt;input type="text" name="name2" aria-labelledby="namelabel"&gt;&lt;/td&gt;
		&lt;td&gt;&lt;input type="text" size="3" name="age2" aria-labelledby="agelabel"&gt;&lt;/td&gt;
		&lt;td&gt;&lt;input type="text" size="4" name="weight2" aria-labelledby="weightlabel"&gt;&lt;/td&gt;
	&lt;/tr&gt;
	&lt;/table&gt','wp-ada-compliance-basic');

/********************************/
// absolute font size 
/********************************/
$wp_ada_compliance_basic_def['absolute_fontsize']['DisplayError'] =__('<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ALERT! One or more instances of an absolute font size were encountered. Using your browser, verify that text can be scaled up to 200% without loss of content or functionality. Check that no text is clipped, truncated, or obscured. To ensure compatibility with older web browsers consider using relative units (such as percents or ems) to specify font sizes rather than absolute units (such as pixels or points). ','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['absolute_fontsize']['StoredError'] = __('An absolute font size was found. Using your browser, verify that text can be scaled up to 200% without loss of content or functionality. Check that no text is clipped, truncated, or obscured. To ensure compatibility with older web browsers consider using relative units (such as percents or ems) to specify font sizes rather than absolute units (such as pixels or points).','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['absolute_fontsize']['Settings'] = __('Absolute font sizes (pts and px). ','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['absolute_fontsize']['Reference'] = __('WCAG 2.1 (Level AA) - 1.4.4','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['absolute_fontsize']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#resize-text';
$wp_ada_compliance_basic_def['absolute_fontsize']['HelpURL'] = 'https://www.w3.org/TR/UNDERSTANDING-WCAG20/visual-audio-contrast-scale.html';
$wp_ada_compliance_basic_def['absolute_fontsize']['HelpINSTR'] = __('Using your browser, verify that text can be scaled up to 200% without loss of content or functionality. Check that no text is clipped, truncated, or obscured. Enable the "Convert absolute font sizes found in content to relative units". If available, use the font size option to change the affected text or switch to text view, locate and change the font size to a relative unit. You should also enable the "Convert font size selector to use relative units" setting to allow selection of relative fonts in the editor.','wp-ada-compliance-basic');

/********************************/
//iframe missing title
/********************************/
$wp_ada_compliance_basic_def['iframe_missing_title']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more frames without a title attribute were encountered. Switch to Text view and add a TITLE attribute with appropriate text to describe the purpose and/or content of the frame to each FRAME and IFRAME element (e.g. &lt;iframe TITLE="Main Content"&gt;). Upgrade to the full version for additional options to automatically correct this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['iframe_missing_title']['StoredError'] = __('No TITLE attributes found for this frame. Switch to Text view and add a TITLE attribute with appropriate text to describe the purpose and/or content of the frame to each FRAME and IFRAME element (e.g. &lt;iframe TITLE="Main Content"&gt;). Upgrade to the full version for additional options to automatically correct this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['iframe_missing_title']['Settings'] = __('Iframe tags without TITLE attributes.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['iframe_missing_title']['Reference'] = __('WCAG 2.1 (Level A) - 1.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['iframe_missing_title']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#non-text-content';
$wp_ada_compliance_basic_def['iframe_missing_title']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/H64.html';
$wp_ada_compliance_basic_def['iframe_missing_title']['HelpINSTR'] = __(' Switch to Text view and add a TITLE attribute with appropriate text to describe the purpose and/or content of the frame to each FRAME and IFRAME element (e.g. &lt;iframe TITLE="Main Content"&gt;). Upgrade to the full version for additional options to automatically correct this issue.','wp-ada-compliance-basic');

/********************************/
// empty heading tag
/********************************/
$wp_ada_compliance_basic_def['empty_heading_tag']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more empty headings were encountered. Some users, especially keyboard and screen reader users, often navigate by heading elements. An empty heading will present no information and may introduce confusion. In order to correct this issue you will need to switch to the Text view to locate and remove the empty tag. Upgrade to the full version for additional options to automatically correct this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_heading_tag']['StoredError'] = __('An empty heading was encountered. Some users, especially keyboard and screen reader users, often navigate by heading elements. An empty heading will present no information and may introduce confusion. To correct this issue switch to the Text view to locate and remove the empty tag. Upgrade to the full version for additional options to automatically correct this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_heading_tag']['Settings'] = __('Empty heading tags.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_heading_tag']['Reference'] = __('WCAG 2.1 (Level A) - 1.3.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['empty_heading_tag']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#info-and-relationships';
$wp_ada_compliance_basic_def['empty_heading_tag']['HelpURL'] = 'http://www.w3.org/TR/UNDERSTANDING-WCAG20/content-structure-separation-programmatic.html';
$wp_ada_compliance_basic_def['empty_heading_tag']['HelpINSTR'] = __('Switch to the Text view to locate and remove the empty tag. Upgrade to the full version for additional options to automatically correct this issue.','wp-ada-compliance-basic');

/********************************/
// missing heading tags
/********************************/
$wp_ada_compliance_basic_def['missing_headings']['DisplayError'] = __('<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ALERT! This page has no headings. This is not a problem if the page does not actually include sections of content but each section of content should begin with an H2 heading element and sub-sections should include subheadings (H3, H4, H5 or H6). Headings should not be used for decorative purposes such as to display text in bold. Check the page for content that should include section headers and add headings as required.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_headings']['StoredError'] = __('This page has no headings. This is not a problem if the page does not actually include sections of content but each section of content should begin with an H2 heading element and sub-sections should include subheadings (H3, H4, H5 or H6). Headings should not be used for decorative purposes such as to display text in bold. Check the page for content that should include section headers and add headings as required.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_headings']['Settings'] = __('Heading tags not used to structure page content.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_headings']['Reference'] = __('WCAG 2.1 (Level A) - 1.3.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['missing_headings']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#info-and-relationships';
$wp_ada_compliance_basic_def['missing_headings']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/H42.html';
$wp_ada_compliance_basic_def['missing_headings']['HelpINSTR'] = __('Check the page for content that should include section headers and revise the page structure to include heading tags if required.','wp-ada-compliance-basic');

/********************************/
// headings that are not in order
/********************************/
$wp_ada_compliance_basic_def['incorrect_heading_order']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! The headings on this page are not nested correctly (ie... H1 before H2, H2 before H3 etc...). Headings communicate the organization of the content on the page and can be used to provide in-page navigation. There should only be one H1 element and it should enclose the page title. Each section of content should begin with a heading H2 element followed by sub-sections marked with subheadings (H3, H4, H5 or H6). Headings should not be used for decorative purposes such as to display text in bold. Edit the page content or theme files to ensure headings are included in the correct nested order (ie... H1 before H2, H2 before H3 etc...) and that there is only one H1 element and it encloses the page title. The one exception is the home page which may have the website title enclosed in the H1 element.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['incorrect_heading_order']['StoredError'] = __('The headings on this page are not nested correctly (ie... H1 before H2, H2 before H3 etc...). Headings communicate the organization of the content on the page and can be used to provide in-page navigation. There should only be one H1 element and it should enclose the page title. Each section of content should begin with a heading H2 element followed by sub-sections marked with subheadings (H3, H4, H5 or H6). Headings should not be used for decorative purposes such as to display text in bold. Edit the page content or theme files to ensure headings are included in the correct nested order (ie... H1 before H2, H2 before H3 etc...) and that there is only one H1 element and it encloses the page title. The one exception is the home page which may have the website title enclosed in the H1 element.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['incorrect_heading_order']['Settings'] = __('Headings included on a page but not nested correctly (ie... H1 before H2, H2 before H3 etc...) or more than one H1 element or page title not enclosed in H1 element.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['incorrect_heading_order']['Reference'] = __('WCAG 2.1 (Level A) - 1.3.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['incorrect_heading_order']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#info-and-relationships';
$wp_ada_compliance_basic_def['incorrect_heading_order']['HelpURL'] = 'https://www.w3.org/WAI/tutorials/page-structure/headings/';
$wp_ada_compliance_basic_def['incorrect_heading_order']['HelpINSTR'] = __('Edit the page content or theme files to ensure headings are included in the correct nested order (ie... H1 before H2, H2 before H3 etc...) and that there is only one H1 element and it encloses the page title. The one exception is the home page which may have the website title enclosed in the H1 element.','wp-ada-compliance-basic');


/********************************/
// empty button
/********************************/
$wp_ada_compliance_basic_def['empty_button_tag']['DisplayError'] =  __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more empty buttons were encountered. If a button contains no text, the function or purpose will not be presented to the user. This can introduce confusion for keyboard and screen reader users. Switch to the Text view to locate and remove the empty button or add an aria-label or title attribute with descriptive text. If the button includes an image that is missing alternate text, use the edit image option to add alt text to the image.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_button_tag']['StoredError'] = __('An empty button was encountered. If a button contains no text, the function or purpose of the button will not be presented to the user. This can introduce confusion for keyboard and screen reader users. Switch to the Text view to locate and remove the empty tag or add an aria-label or title attribute with descriptive text. If the button includes an image that is missing alternate text, use the edit image option to add alt text to the image.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_button_tag']['Settings'] = __('Empty button tags.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_button_tag']['Reference'] = __('WCAG 2.1 (Level A) - 1.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['empty_button_tag']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#non-text-content';
$wp_ada_compliance_basic_def['empty_button_tag']['HelpURL'] = 'http://www.w3.org/TR/UNDERSTANDING-WCAG20/text-equiv-all.html';
$wp_ada_compliance_basic_def['empty_button_tag']['HelpINSTR'] = __('Switch to the Text view to locate and remove the empty tag or add an aria-label or title attribute with descriptive text. If the button includes an image that is missing alternate text, use the edit image option to add alt text to the image.','wp-ada-compliance-basic');

/********************************/
// empty a:link tags 
/********************************/
$wp_ada_compliance_basic_def['empty_anchor_tag']['DisplayError'] =  __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more empty links were encountered. If a link contains no text, the function or purpose of the link will not be presented to the user. This can introduce confusion for keyboard and screen reader users. Switch to the Text view to locate and remove the empty tag or add an aria-label or title attribute with descriptive text. If the link includes an image that is missing alternate text, use the edit image option to add alt text to the image. Upgrade to the full version for additional options to automatically correct this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_anchor_tag']['StoredError'] = __('An empty link was encountered. If a link contains no text, the function or purpose of the link will not be presented to the user. This can introduce confusion for keyboard and screen reader users. Switch to the Text view to locate and remove the empty tag or add an aria-label or title attribute with descriptive text. If the link includes an image that is missing alternate text, use the edit image option to add alt text to the image. Upgrade to the full version for additional options to automatically correct this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_anchor_tag']['Settings'] = __('Empty anchor tags or links.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_anchor_tag']['Reference'] = __('WCAG 2.1 (Level A) - 2.4.4','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['empty_anchor_tag']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#link-purpose-in-context';
$wp_ada_compliance_basic_def['empty_anchor_tag']['HelpURL'] = 'http://www.w3.org/TR/UNDERSTANDING-WCAG20/navigation-mechanisms-refs.html';
$wp_ada_compliance_basic_def['empty_anchor_tag']['HelpINSTR'] = __('Switch to the Text view to locate and remove the empty tag or add an aria-label or title attribute with descriptive text. If the link includes an image that is missing alternate text, use the edit image option to add alt text to the image. Upgrade to the full version for additional options to automatically correct this issue.','wp-ada-compliance-basic');

/********************************/
// empty href attribute 
/********************************/
$wp_ada_compliance_basic_def['empty_href']['DisplayError'] =  __('<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ALERT! One or more anchor tags with empty href attributes were encountered. If clicking a link results in no action it will create confusion for screen reader users. If no longer needed remove the link or if this link is used as a trigger to display dynamic content such as a menu or accordion this error can be ignored.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_href']['StoredError'] = __('An anchor tag with an empty href attribute was encountered. If clicking a link results in no action it will create confusion for screen reader users. If no longer needed remove the link or if this link is used as a trigger to display dynamic content such as a menu or accordion this error can be ignored.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_href']['Settings'] = __('Empty href attributes.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_href']['Reference'] = __('WCAG 2.1 (Level A) - 2.4.4','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['empty_href']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#link-purpose-in-context';
$wp_ada_compliance_basic_def['empty_href']['HelpURL'] = 'http://www.w3.org/TR/UNDERSTANDING-WCAG20/navigation-mechanisms-refs.html';
$wp_ada_compliance_basic_def['empty_href']['HelpINSTR'] = __('Verify that an action is triggered when a user clicks the link. If not, switch to the Text view, locate and remove the link.','wp-ada-compliance-basic');

/********************************/
//ambiguous link text
/********************************/
$wp_ada_compliance_basic_def['ambiguous_anchor_tag']['DisplayError'] =  __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more links were encountered with ambiguous link text. Links should make sense out of context. Using a URL as link text or phrases such as "click here", "more", "click for details" are ambiguous when read out of context. Ensure this link is included in a paragraph, list or table cell with preceding text that describes its purpose or is in a table cell with a header that explains the purpose of the link. If a link encloses an image the image alt text should include the purpose of the link and should not include words such as "logo" unless linking to a full size logo. To increase accessibility use existing words or text in your page as the link rather than the ambiguous phrase. (e.g. Rather than click here to view the annual report, use View the annual report. )','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['ambiguous_anchor_tag']['StoredError'] = __('A link was encountered with ambiguous link text. Links should make sense out of context. Using a URL as link text or phrases such as "click here", "more", "click for details" are ambiguous when read out of context. Ensure this link is included in a paragraph, list or table cell with preceding text that describes its purpose or is in a table cell with a header that explains the purpose of the link. If a link encloses an image the image alt text should include the purpose of the link and should not include words such as "logo" unless linking to a full size logo. To increase accessibility use existing words or text in your page as the link rather than the ambiguous phrase. (e.g. Rather than click here to view the annual report, use View the annual report. )','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['ambiguous_anchor_tag']['Settings'] = __('Links with ambiguous link text such as a "click here", "more", or "click for details".','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['ambiguous_anchor_tag']['Reference'] = __('WCAG 2.1 (Level A) - 2.4.4','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['ambiguous_anchor_tag']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#link-purpose-in-context';
$wp_ada_compliance_basic_def['ambiguous_anchor_tag']['HelpURL'] = 'http://www.w3.org/TR/UNDERSTANDING-WCAG20/navigation-mechanisms-refs.html';
$wp_ada_compliance_basic_def['ambiguous_anchor_tag']['HelpINSTR'] = __('Links should make sense out of context. Using a URL as link text or phrases such as "click here", "more", "click for details" are ambiguous when read out of context. Ensure this link is included in a paragraph, list or table cell with preceding text that describes its purpose or is in a table cell with a header that explains the purpose of the link. If a link encloses an image the image alt text should include the purpose of the link and should not include words such as "logo" unless linking to a full size logo. To increase accessibility use existing words or text in your page as the link rather than the ambiguous phrase. (e.g. Rather than click here to view the annual report, use View the annual report. ). The issue can also be corrected by adding title or aria-label attributes to the link to provide additional link text (e.g. &lt;a href="http://www.google.com" aria-label="Annual Report" &gt;Click Here&lt;a&gt;).','wp-ada-compliance-basic');

/********************************/
//missing alt text on image inside content
/********************************/
$wp_ada_compliance_basic_def['img_missing_alt']['DisplayError'] =  __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more images were found without alternate text. Without alternative text, the content of an image will not be available to screen reader users or when the image is unavailable. Each image that conveys meaning or is used as link content must include alternate text unless including the alternate text would create redundancy (e.g. when text is also included in an image caption or text inside the body of a link) or the image is included for decoration. If this image conveys meaning, use the edit image option to add alt text to the image.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_missing_alt']['StoredError'] = __('Image found without alternate text. Without alternative text, the content of an image will not be available to screen reader users or when the image is unavailable. Each image that conveys meaning or is used as link content must include alternate text unless including the alternate text would create redundancy (e.g. when text is also included in an image caption or text inside the body of a link) or the image is included for decoration. If this image conveys meaning, use the edit image option to add alt text to the image.','wp-ada-compliance-basic','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_missing_alt']['Settings'] = __('Images found without alternate text.','wp-ada-compliance-basic','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_missing_alt']['Reference'] = __('WCAG 2.1 (Level A) - 1.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['img_missing_alt']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#non-text-content';
$wp_ada_compliance_basic_def['img_missing_alt']['HelpURL'] = 'http://www.w3.org/TR/UNDERSTANDING-WCAG20/text-equiv-all.html';
$wp_ada_compliance_basic_def['img_missing_alt']['HelpINSTR'] = __('The alt attribute should be blank for decorative images or if alternate text would create redundancy. Decorative images don’t add information to the content of a page. For example, the information provided by the image might already be given using adjacent text, or the image might be included to make the website more visually attractive. If this image conveys meaning, use the edit image option to add alt text to the image.','wp-ada-compliance-basic');

/********************************/
//empty alt text on image
/********************************/
$wp_ada_compliance_basic_def['img_empty_alt']['DisplayError'] =  __('<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ALERT! One or more images were found with empty alternate text. Empty alt text is not a problem if the image does not convey meaning, is not inside an anchor tag, used for form input or is for decoration only. Without alternative text, the content of an image will not be available to screen reader users or when the image is unavailable. Each image that conveys meaning or is used as link content must include alternate text unless including the alternate text would create redundancy (e.g. when text is also included in an image caption or text inside the body of a link) or the image is included for decoration. If this image conveys meaning, use the edit image option to add alt text to the image otherwise it is safe to ignore this error or alternatively you may switch to Text view and add role="presentation" to the image tag.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_empty_alt']['StoredError'] = __('Image found with empty alternate text. Empty alt text is not a problem if the image does not convey meaning, is not inside an anchor tag, used for form input or is for decoration only. Without alternative text, the content of an image will not be available to screen reader users or when the image is unavailable. Each image that conveys meaning or is used as link content must include alternate text unless including the alternate text would create redundancy (e.g. when text is also included in an image caption or text inside the body of a link) or the image is included for decoration. If this image conveys meaning, use the edit image option to add alt text to the image otherwise it is safe to ignore this error or alternatively you may switch to Text view and add role="presentation" to the image tag.','wp-ada-compliance-basic','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_empty_alt']['Settings'] = __('Image found with empty alternate text.','wp-ada-compliance-basic','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_empty_alt']['Reference'] = __('WCAG 2.1 (Level A) - 1.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['img_empty_alt']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#non-text-content';
$wp_ada_compliance_basic_def['img_empty_alt']['HelpURL'] = 'http://www.w3.org/TR/UNDERSTANDING-WCAG20/text-equiv-all.html';
$wp_ada_compliance_basic_def['img_empty_alt']['HelpINSTR'] = __('The alt attribute should be blank for decorative images or if alternate text would create redundancy. Decorative images don’t add information to the content of a page. For example, the information provided by the image might already be given using adjacent text, or the image might be included to make the website more visually attractive. If this image conveys meaning, use the edit image option to add alt text to the image otherwise it is safe to ignore this error or alternatively you may switch to Text view and add role="presentation" to the image tag.','wp-ada-compliance-basic');



/************************************************************/
//empty alt text on image with non empty title or aria label
/***********************************************************/
$wp_ada_compliance_basic_def['img_empty_alt_with_title']['DisplayError'] =  __('<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ALERT! One or more images were found with empty alternate text but non-empty title or aria-label attributes. Empty alt text is not a problem if the image does not convey meaning, is not inside an anchor tag or is for decoration only. If the image includes content that should be read aloud include alternate text unless including the alternate text would create redundancy (e.g. when text is also included in an image caption or as text inside the body of a link). Use the edit image option to remove any title or aria-label attributes or add alternate text to the image as applicable.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_empty_alt_with_title']['StoredError'] = __('Image found with empty alternate text but non-empty title or aria-label attributes. Empty alt text is not a problem if the image does not convey meaning, is not inside an anchor tag or is for decoration only. If the image includes content that should be read aloud include alternate text unless including the alternate text would create redundancy (e.g. when text is also included in an image caption or as text inside the body of a link). Use the edit image option to remove any title or aria-label attributes or add alternate text to the image as applicable.','wp-ada-compliance-basic','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_empty_alt_with_title']['Settings'] = __('Images found with empty alternate text but non-empty title or aria-label.','wp-ada-compliance-basic','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_empty_alt_with_title']['Reference'] = __('WCAG 2.1 (Level A) - 1.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['img_empty_alt_with_title']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#non-text-content';
$wp_ada_compliance_basic_def['img_empty_alt_with_title']['HelpURL'] = 'http://www.w3.org/TR/UNDERSTANDING-WCAG20/text-equiv-all.html';
$wp_ada_compliance_basic_def['img_empty_alt_with_title']['HelpINSTR'] = __('The alt, title and aria-label attributes should be blank for decorative images or if alternate text would create redundancy. Decorative images don’t add information to the content of a page. For example, the information provided by the image might already be given using adjacent text, or the image might be included to make the website more visually attractive. Use the edit image option to remove any title or aria-label attributes or add alternate text to the image as applicable.','wp-ada-compliance-basic');


/********************************/
//missing table header
/********************************/
$ada_compliance_dynamic_text =  __(' switch to Text view and add role="presentation" to the table tag (e.g. &lt;table role="presentation"&gt;). Upgrade to the full version for additional options to correct this issue. If it is not practical to mark the table as a "Presentation Table" it is also acceptable to ignore this error.',"wp-ada-compliance-basic");	
$wp_ada_compliance_basic_def['missing_th']['DisplayError'] =  __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more tables without Headers were encountered. If this is a data table, headings and scope should be added to the appropriate cells. If this is not a data table and used only for layout, ','wp-ada-compliance-basic').$ada_compliance_dynamic_text;

$wp_ada_compliance_basic_def['missing_th']['StoredError'] = __('Table without Headers was encountered. If this is a data table, headings and scope should be added to the appropriate cells. If this is not a data table and used only for layout, ','wp-ada-compliance-basic').$ada_compliance_dynamic_text;

$wp_ada_compliance_basic_def['missing_th']['Settings'] = __('Tables without Header cells.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_th']['Reference'] = __('WCAG 2.1 (Level A) - 1.3.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['missing_th']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#info-and-relationships';
$wp_ada_compliance_basic_def['missing_th']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/H51.html';
$wp_ada_compliance_basic_def['missing_th']['HelpINSTR'] = __('To set a table header select one or more table cells and click on the insert table icon. Place your cursor over Cell and choose “Table cell properties”. For Cell type, choose “Header cell”. Set Scope to “Column” if the header is at the top of the table or “Row” if the header is on the left.  If this is not a data table and used only for presentation, ','wp-ada-compliance-basic').$ada_compliance_dynamic_text;


/********************************/
// check for links using target _blank
/********************************/
$wp_ada_compliance_basic_def['new_window_tag']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more anchor tags are set to open a new window. Opening a new window without first notifying the user can disorient users. Edit the link and uncheck the "Open link in new tab" option or add a notice such as (opens in a new window) to the link text or title attribute. CSS may be used to show the notice when the link is active or a small icon may be used inside the anchor tag with the alternate text set to "opens in a new window". (e.g. &lt;a href="http://www.google.com" &gt;Link text &lt;img src="images/newwindow.png" alt="opens in a new window" &gt; &lt;a&gt;.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['new_window_tag']['StoredError'] = __('An anchor tag is set to open a new window. Opening a new window without first notifying the user can disorient users. Edit the link and uncheck the "Open link in new tab" option or add a notice such as (opens in a new window) to the link text or title attribute. CSS may be used to show the notice when the link is active or a small icon may be used inside the anchor tag with the alternate text set to "opens in a new window". (e.g. &lt;a href="http://www.google.com" &gt;Link text &lt;img src="images/newwindow.png" alt="opens in a new window" &gt; &lt;a&gt;.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['new_window_tag']['Settings'] = __('Anchor tag set to open a new window without first notifying the user.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['new_window_tag']['Reference'] = __('WCAG 2.1 (Level A) - 3.2.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['new_window_tag']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#on-focus';
$wp_ada_compliance_basic_def['new_window_tag']['HelpURL'] = 'http://www.w3.org/TR/UNDERSTANDING-WCAG20/consistent-behavior-receive-focus.html';
$wp_ada_compliance_basic_def['new_window_tag']['HelpINSTR'] = __('Edit the link and uncheck the "Open link in new tab" option or add a notice such as (opens in a new window) to the link text or title attribute. CSS may be used to show the notice when the link is active or a small icon may be used inside the anchor tag with the alternate text set to "opens in a new window". (e.g. &lt;a href="http://www.google.com" &gt;Link text &lt;img src="images/newwindow.png" alt="opens in a new window" &gt; &lt;a&gt;.','wp-ada-compliance-basic');

/*****************************************************/
// check for forms that change context without notice
/***************************************************/
$wp_ada_compliance_basic_def['context_change_form']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more form fields trigger an context change. When changes to the value of a field or its focus triggers a new window to open or the form to submit users can become disoriented. Instead of using onchange or onclick attributes on form fields use a submit button.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['context_change_form']['StoredError'] = __('A form field triggers a context change without notice.  When changes to the value of a field or its focus triggers a new window to open or the form to submit users can become disoriented. Instead of using onchange or onclick attributes on form fields use a submit button.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['context_change_form']['Settings'] = __('Form fields that trigger a context change without notice.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['context_change_form']['Reference'] = __('WCAG 2.1 (Level A) - 3.2.2','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['context_change_form']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#on-input';
$wp_ada_compliance_basic_def['context_change_form']['HelpURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#on-input';
$wp_ada_compliance_basic_def['context_change_form']['HelpINSTR'] = __('Edit the affected form to remove onchange, onclick, onkeypress or onfocus attributes from form fileds and add a submit button.','wp-ada-compliance-basic');

/*****************************************************/
// validate elments with onclick but not onkeypress
/***************************************************/
$wp_ada_compliance_basic_def['missing_onkeypress']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more page elements use a pointing device event handler but fails to include an equivalent keyboard event handler. Users without vision rely on the keyboard for navigation, without a keyboard event handler they are unable to navigate your pages. To correct this issues add an equivalent keyboard event handler. (i.e... onkeypress, onkeydown, onblur, etc...)','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_onkeypress']['StoredError'] = __('A page element uses a pointing device event handler but fails to include an equivalent keyboard event handler. Users without vision rely on the keyboard for navigation, without a keyboard event handler they are unable to navigate your pages. To correct this issues add an equivalent keyboard event handler. (i.e... onkeypress, onkeydown, onblur, etc...)','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_onkeypress']['Settings'] = __('Elements with pointing device event handlers but do no equivalent keyboard event handler.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_onkeypress']['Reference'] = __('WCAG 2.1 (Level A) - 2.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['missing_onkeypress']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#keyboard';
$wp_ada_compliance_basic_def['missing_onkeypress']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/F54.html';
$wp_ada_compliance_basic_def['missing_onkeypress']['HelpINSTR'] = __('Edit the affected code to add an equivalent keyboard event handler. (i.e... onkeypress, onkeydown, onblur, etc...)','wp-ada-compliance-basic');

/*****************************************************/
// validate links without visual cue
/***************************************************/
$wp_ada_compliance_basic_def['link_without_visual_cue']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more anchor tags were found with the underline removed using a text-decoration style. While some links may be visually evident from page design and context, such as navigational links, links within text are often visually understood only from their own display attributes. Ensure that all links are visually identifiable via some other means such as location in page, underlined, bolded, italicized, sufficient difference in lightness (minimum contrast of 3:1).','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['link_without_visual_cue']['StoredError'] = __('An anchor tag was found with the underline removed using a text-decoration style. While some links may be visually evident from page design and context, such as navigational links, links within text are often visually understood only from their own display attributes. Ensure that all links are visually identifiable via some other means such as location in page, underlined, bolded, italicized, sufficient difference in lightness (minimum contrast of 3:1).','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['link_without_visual_cue']['Settings'] = __('Anchor text without underline or other means of identifying them from surrounding text.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['link_without_visual_cue']['Reference'] = __('WCAG 2.1 (Level A) - 1.4.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['link_without_visual_cue']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#use-of-color';
$wp_ada_compliance_basic_def['link_without_visual_cue']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/F73.html';
$wp_ada_compliance_basic_def['link_without_visual_cue']['HelpINSTR'] = __('Edit code to include "text-decoration: underline" or verify that all links are visually identifiable via some other means such as location in page, underlined, bolded, italicized, sufficient difference in lightness (minimum contrast of 3:1).','wp-ada-compliance-basic');

/********************************/
// image files that link to self
/********************************/
$wp_ada_compliance_basic_def['img_linked_to_self']['DisplayError'] = __('<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ALERT! One or more images were found linking to themselves. This is not a problem unless the image does not include alternate text indicating the purpose of the link and content of the linked image. Review the alternate text for this image to ensure it clearly conveys the purpose of the link and content of the image. Due to Wordpress default behaviors this often occurs unknowingly. Consider removing the link if it is not required.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_linked_to_self']['StoredError'] = __('An image was found linking to itself. This is not a problem unless the image does not include alternate text indicating the purpose of the link and content of the linked image. Review the alternate text for this image to ensure it clearly conveys the purpose of the link and content of the image. Due to Wordpress default behaviors this often occurs unknowingly. Consider removing the link if it is not required.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_linked_to_self']['Settings'] = __('Image linking to itself.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['img_linked_to_self']['Reference'] = __('WCAG 2.1 (Level A) - 1.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['img_linked_to_self']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#non-text-content';
$wp_ada_compliance_basic_def['img_linked_to_self']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/H37.html';
$wp_ada_compliance_basic_def['img_linked_to_self']['HelpINSTR'] = __('Edit theme files to include skip navigation links.','wp-ada-compliance-basic');

/********************************/
// animated images
/********************************/
$wp_ada_compliance_basic_def['animated_image']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more animated images were found. Animated images that repeat non-stop or more than 3 times in one second should be avoided. Check that the image does not repeat nonstop or flash more than 3 times in one second. Consider replacing animated images with static images.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['animated_image']['StoredError'] = __('An animated image was found. Animated images that repeat non-stop or more than 3 times in one second should be avoided. Check that the image does not repeat nonstop or flash more than 3 times in one second. Consider replacing animated images with static images.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['animated_image']['Settings'] = __('Animated images that repeat non stop or flash more than 3 times per second.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['animated_image']['Reference'] = __('WCAG 2.1 (Level A) - 2.3.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['animated_image']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#three-flashes-or-below-threshold';
$wp_ada_compliance_basic_def['animated_image']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/G152.html';
$wp_ada_compliance_basic_def['animated_image']['HelpINSTR'] = __(' Check that the image does not repeat nonstop or flash more than 3 times in one second. Consider replacing animated images with static images.','wp-ada-compliance-basic');

/********************************/
// color contrast failure
/********************************/
$wp_ada_compliance_basic_def['color_contrast_failure']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more elements were found to not have a contrast ratio of at least 4.5:1 between the foreground and background colors. Verify that both foreground and background colors are specified at some level by stylesheets or through inheritance rules and that the contrast ratio is at least 4.5:1. It is not necessary that the foreground and background colors both be defined on the same CSS rule. Use the WAVE by WebAim color contrast checker for greater accuracy.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['color_contrast_failure']['StoredError'] = __('An element was found to not have a contrast ratio of at least 4.5:1 between the foreground and background colors. Verify that both foreground and background colors are specified at some level by stylesheets or through inheritance rules and that the contrast ratio is at least 4.5:1. It is not necessary that the foreground and background colors both be defined on the same CSS rule. Use the WAVE by WebAim color contrast checker for greater accuracy.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['color_contrast_failure']['Settings'] = __('Foreground and background colors with a contrast ratio of at least 4.5:1','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['color_contrast_failure']['Reference'] = __('WCAG 2.1 (Level A) - 1.4.3','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['color_contrast_failure']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#contrast-minimum';
$wp_ada_compliance_basic_def['color_contrast_failure']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/G18.html';
$wp_ada_compliance_basic_def['color_contrast_failure']['HelpINSTR'] = __('Verify that both foreground and background colors are specified at some level by stylesheets or through inheritance rules and that the contrast ratio is at least 4.5:1. Use the WAVE by WebAim color contrast checker for assitance. ','wp-ada-compliance-basic');

/********************************/
// link color contrast failure
/********************************/
$wp_ada_compliance_basic_def['link_color_contrast_failure']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more links were found to not have a contrast ratio of at least 3:1 between them and the surrounding text. Ensure all links are visually identifiable via some other means such as location in page (i.e...menu), underlined, bolded, italicized, sufficient difference in lightness (minimum color contrast ratio of 3:1). If color difference is used, a visual highlight must be used when hovering over the link. Acceptable effects include underline, bold or italics or increased font size.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['link_color_contrast_failure']['StoredError'] = __('A link was found to not have a contrast ratio of at least 3:1 between it and the surrounding text. Ensure all links are visually identifiable via some other means such as location in page (i.e...menu), underlined, bolded, italicized, sufficient difference in lightness (minimum color contrast ratio of 3:1). If color difference is used, a visual highlight must be used when hovering over the link. Acceptable effects include underline, bold or italics or increased font size.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['link_color_contrast_failure']['Settings'] = __('Links without a contrast ratio of at least 3:1 with surrounding text','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['link_color_contrast_failure']['Reference'] = __('WCAG 2.1 (Level A) - 1.4.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['link_color_contrast_failure']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/?showtechniques=141#use-of-color';
$wp_ada_compliance_basic_def['link_color_contrast_failure']['HelpURL'] = 'https://www.w3.org/WAI/WCAG21/Techniques/general/G183.html';
$wp_ada_compliance_basic_def['link_color_contrast_failure']['HelpINSTR'] = __('Ensure link is visually identifiable via some other means such as location in page (i.e...menu), underlined, bolded, italicized, sufficient difference in lightness (minimum color contrast ratio of 3:1). If color difference is used, a visual highlight must be used when hovering over the link. Acceptable effects include underline, bold or italics or increased font size. ','wp-ada-compliance-basic');

/********************************/
// blinking text
/********************************/
$wp_ada_compliance_basic_def['blinking_text']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more elements were found with an html blink tag or having CSS text-decoration: blink applied. When applied it causes any text inside the element to blink at a predetermined rate. This cannot be interrupted by the user, nor can it be disabled as a preference. The blinking continues as long as the page is displayed.  Locate the affected code and remove the blink tag or CSS text-decoration rule.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['blinking_text']['StoredError'] = __('An element was found with an html blink tag or having CSS text-decoration: blink applied. When applied it causes any text inside the element to blink at a predetermined rate. This cannot be interrupted by the user, nor can it be disabled as a preference. The blinking continues as long as the page is displayed.  Locate the affected code and remove the blink tag or CSS text-decoration rule. ','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['blinking_text']['Settings'] = __('Elements set to blink without a mechanism to pause or stop.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['blinking_text']['Reference'] = __('WCAG 2.1 (Level A) - 2.2.2','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['blinking_text']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#timing-adjustable';
$wp_ada_compliance_basic_def['blinking_text']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/G187.html';
$wp_ada_compliance_basic_def['blinking_text']['HelpINSTR'] = __('Locate the affected code and remove the blink tag or CSS text-decoration rule.','wp-ada-compliance-basic');

/********************************/
// links and focus styles where visual focus indication has been removed.
/********************************/
$wp_ada_compliance_basic_def['visual_focus_removed']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more CSS styles were found to be removing or obscuring visual indications that a link or form field is selected. Using a border or outline style that obscures the focus indicator causes problems for keyboard-only users. Using keyboard tabs and mouse over, verify that the affected element includes a visible outline when in focus and that it has a contrast ratio of at least 3:1 between its pixels in the focused and unfocused states and against adjacent colors. If no visible outline exists, change the style to avoid obscuring the focus outline.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['visual_focus_removed']['StoredError'] = __('A CSS style was found to be removing or obscuring visual indications that the link or form field is selected. Using a border or outline style that obscures the focus indicator causes problems for keyboard-only users. Using keyboard tabs and mouse over, verify that the affected element includes a visible outline when in focus and that it has a contrast ratio of at least 3:1 between its pixels in the focused and unfocused states and against adjacent colors. If no visible outline exists, change the style to avoid obscuring the focus outline.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['visual_focus_removed']['Settings'] = __('Links or form field styles that remove visual indications of focus when selected.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['visual_focus_removed']['Reference'] = __('WCAG 2.1 (Level AA) - 2.4.7','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['visual_focus_removed']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#focus-visible';
$wp_ada_compliance_basic_def['visual_focus_removed']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/F78.html';
$wp_ada_compliance_basic_def['visual_focus_removed']['HelpINSTR'] = __('Using keyboard tabs and mouse over, verify that the affected element includes a visible outline when in focus. If no visible outline exists, change the style to avoid obscuring the focus outline.','wp-ada-compliance-basic');

/********************************/
//look for tags with onclick used to emulate links
/********************************/
$wp_ada_compliance_basic_def['emulating_links']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more elements are using an event handler such as onclick to emulate a link. A link created in this manner cannot be tabbed to from the keyboard and does not gain keyboard focus like other controls and/or links. Change affected elements to anchor tags or include role="link" and tabindex="0" as attributes.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['emulating_links']['StoredError'] = __('An element was found to be using an event handler such as onclick to emulate a link. A link created in this manner cannot be tabbed to from the keyboard and does not gain keyboard focus like other controls and/or links. Change this element to an anchor tag or include role="link" and tabindex="0" as attributes.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['emulating_links']['Settings'] = __('Non-anchor tags with event handlers used to emulate links.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['emulating_links']['Reference'] = __('WCAG 2.1 (Level AA) - 2.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['emulating_links']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#keyboard';
$wp_ada_compliance_basic_def['emulating_links']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/F42.html';
$wp_ada_compliance_basic_def['emulating_links']['HelpINSTR'] = __('Change this element to an anchor tag or include role="link" and tabindex="0" as attributes.','wp-ada-compliance-basic');

/********************************/
// tab order modified using tabindex
/********************************/
$wp_ada_compliance_basic_def['tab_order_modified']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more elements were found with a tabindex attribute. The tabindex attribute is used to manually specify the order links and form fields should receive focus when using the tab key on the keyboard. Using tabindex to modify the tab order is not always a problem but because its use can become unmaintainable very quickly and it is easily forgotten when content is changed, its use should be avoided. Check that the tab order specified by the tabindex attributes follows relationships in the content or remove the tabindex attribute from your pages.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['tab_order_modified']['StoredError'] = __('An element was found with a tabindex attribute. The tabindex attribute is used to manually specify the order links and form fields should receive focus when using the tab key on the keyboard. Using tabindex to modify the tab order is not always a problem but because its use can become unmaintainable very quickly and it is easily forgotten when content is changed, its use should be avoided. Check that the tab order specified by the tabindex attributes follows relationships in the content or remove the tabindex attribute from your pages.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['tab_order_modified']['Settings'] = __('Tab order changed using the tabindex attribute.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['tab_order_modified']['Reference'] = __('WCAG 2.1 (Level A) - 2.4.3','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['tab_order_modified']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#focus-order';
$wp_ada_compliance_basic_def['tab_order_modified']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/F44.html';
$wp_ada_compliance_basic_def['tab_order_modified']['HelpINSTR'] = __('Check that the tab order specified by the tabindex attributes follows relationships in the content or remove the tabindex attribute from your pages.','wp-ada-compliance-basic');

/********************************/
//justified text
/********************************/
$wp_ada_compliance_basic_def['text_justified']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING!  One or more instances of justified text were encountered. Many people with cognitive disabilities have a great deal of trouble with blocks of text that are justified (aligned to both the left and the right margins). Use either "left" or "right" alignment or remove it all together.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['text_justified']['StoredError'] = __('Text was found to be justified. Many people with cognitive disabilities have a great deal of trouble with blocks of text that are justified (aligned to both the left and the right margins). Use either "left" or "right" alignment or remove it all together.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['text_justified']['Settings'] = __('Text that is justified using align or text-align elements.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['text_justified']['Reference'] = __('WCAG 2.1 (Level AAA) - 1.4.8','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['text_justified']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#visual-presentation';
$wp_ada_compliance_basic_def['text_justified']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/F88.html';
$wp_ada_compliance_basic_def['text_justified']['HelpINSTR'] = __('If available, use the alignment option to change the affected text or switch to text view, locate and remove "align: justify" or "text-align: justify". Upgrade to the full version for additional options to automatically correct this issue.','wp-ada-compliance-basic');

/*****************************************************/
// validate links without href but that include event handlers
/***************************************************/
$wp_ada_compliance_basic_def['missing_href']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more links were found with event handlers and no href attribute. Without an href attribute links are ignored by screen readers. To correct this issues set the href value or add a role attribute that is set to "link". (i.e... role="link" or href="http://www.google.com")','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_href']['StoredError'] = __('A link was found with event handlers and no href attribute. Without an href attribute links are ignored by screen readers. To correct this issues set the href value or add a role attribute that is set to "link". (i.e... role="link" or href="http://www.google.com")','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_href']['Settings'] = __('Links with event handlers and no href attribute.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['missing_href']['Reference'] = __('WCAG 2.1 (Level A) - 2.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['missing_href']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#keyboard';
$wp_ada_compliance_basic_def['missing_href']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/F54.html';
$wp_ada_compliance_basic_def['missing_href']['HelpINSTR'] = __('Set the href value or add a role attribute that is set to "link". (i.e... role="link" or href="http://www.google.com")','wp-ada-compliance-basic');

/********************************/
//links in content that are lot included in an anchor tag
/********************************/
$wp_ada_compliance_basic_def['unlinked_anchors']['DisplayError'] =  __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more links were found to be inaccessible to keyboard users. Links should be enclosed in an anchor tag and make sense when read out of context. Unless it is an email address the URL itself should never be used as link text as it would be ambiguous to screen reader users. To correct this issue, create clickable links with these urls.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['unlinked_anchors']['StoredError'] = __('A link was found to be inaccessible to keyboard users. Links should be enclosed in an anchor tag and make sense when read out of context. Unless it is an email address the URL itself should never be used as link text as it would be ambiguous to screen reader users. To correct this issue, create clickable links with these urls.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['unlinked_anchors']['Settings'] = __('Links that are inaccessabile to screen reader users.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['unlinked_anchors']['Reference'] = __('WCAG 2.1.1 (Level A) - 2.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['unlinked_anchors']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#keyboard';
$wp_ada_compliance_basic_def['unlinked_anchors']['HelpURL'] = 'https://www.w3.org/WAI/WCAG21/Techniques/general/G202.html';
$wp_ada_compliance_basic_def['unlinked_anchors']['HelpINSTR'] = __('To correct this issue, create clickable links from these urls.','wp-ada-compliance-basic');

/********************************/
// elementor galleries
/********************************/

$wp_ada_compliance_basic_def['elementor_gallery']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING!  One or more Elementor Gallery widgets were found. Elementor Galleries use the CSS background-image property to place image thumbnails, resulting in empty anchor text and images that can not be identified by screen readers. Choose an alternative Gallery widget or plugin.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['elementor_gallery']['StoredError'] = __('An Elementor Gallery widget was found. Elementor Galleries use the CSS background-image property to place image thumbnails, resulting in empty anchor text and images that can not be identified by screen readers. Choose an alternative Gallery widget or plugin','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['elementor_gallery']['Settings'] = __('Use of inaccessible Elementor Gallery','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['elementor_gallery']['Reference'] = __('WCAG 2.1 (Level A) - 1.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['elementor_gallery']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#non-text-content';
$wp_ada_compliance_basic_def['elementor_gallery']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/F3.html';
$wp_ada_compliance_basic_def['elementor_gallery']['HelpINSTR'] = __('Choose an alternative Gallery widget or plugin.','wp-ada-compliance-basic');

/********************************/
// adjacent identical links
/********************************/
$wp_ada_compliance_basic_def['adjacent_identical_links']['DisplayError'] = __('<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ALERT! One or more anchor tags were found adjacent to one another with the same link text and destination. This can be confusing to screen reader users. Consider removing redundant links, adding a title attribute with additional descriptive text or combine the links into a single anchor tag enclosing both elements. When combining links that include an image tag the image alt attribute should be set to empty i.e... alt="".','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['adjacent_identical_links']['StoredError'] = __('One or more anchor tags were found adjacent to one another with the same link text and destination. This can be confusing to screen reader users. Consider removing redundant links, adding a title attribute with additional descriptive text or combine the links into a single anchor tag enclosing both elements. When combining links that include an image tag the image alt attribute should be set to empty i.e... alt="".','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['adjacent_identical_links']['Settings'] = __('Adjacent anchor tags with the same link text and destination.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['adjacent_identical_links']['Reference'] = __('WCAG 2.1 (Level AA) - 2.4.4','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['adjacent_identical_links']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#link-purpose-in-context';
$wp_ada_compliance_basic_def['adjacent_identical_links']['HelpURL'] = 'https://www.w3.org/WAI/WCAG21/Techniques/html/H2.html';
$wp_ada_compliance_basic_def['adjacent_identical_links']['HelpINSTR'] = __('Consider removing redundant links, adding a title attribute with additional descriptive text or combine the links into a single anchor tag enclosing both elements. When combining links that include an image tag the image alt attribute should be set to empty i.e... alt="".','wp-ada-compliance-basic');

/********************************/
//validate empty th cells
/********************************/
$wp_ada_compliance_basic_def['empty_th']['DisplayError'] =  __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more table header cells contain no text. Table headers associate table data with the correct table header so that screen reader users can understand the data presented. If the table cell is a header, provide text within the cell to describes the column or row. If the cell is not a header make the cell a &lt;td&gt; rather than a &lt;th&gt;','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_th']['StoredError'] = __('A table header cell contains no text. Table headers associate table data with the correct table header so that screen reader users can understand the data presented. If this is a header cell, include text in the cell to describe the column or row. If it is not a table header, change the &lt;th&gt; tag to &lt;td&gt; and remove any scope attributes.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_th']['Settings'] = __('Empty table header cells','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['empty_th']['Reference'] = __('WCAG 2.1 (Level A) - 1.3.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['empty_th']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#info-and-relationships';
$wp_ada_compliance_basic_def['empty_th']['HelpURL'] = 'https://www.w3.org/TR/2016/NOTE-WCAG20-TECHS-20161007/H51';
$wp_ada_compliance_basic_def['empty_th']['HelpINSTR'] = __('If this is a header cell, include text in the cell to describe the column or row. If it is not a table header, change the &lt;th&gt; tag to &lt;td&gt; and remove any scope attributes.','wp-ada-compliance-basic');

/**********************************************************/
// check for links to non html content
/*********************************************************/
$wp_ada_compliance_basic_def['link_to_non_html_content']['DisplayError'] = __('<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ALERT! One or more anchor tags point to non html content such as PDF or MS Word documents. While not required to comply with web accessibility standards these files often have accessibility issues or may be opened in a separate application causing confusion for users. Edit the link to add a notice such as (PDF) to the link text or title attribute. Ensure that the document complies with accessibility standards. ADA compliance for these documents must be verified using the native software such as Adobe Acrobat or MS Word.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['link_to_non_html_content']['StoredError'] = __('An anchor tag points to non html content such as to a PDF or MS Word document. While not required to comply with web accessibility standards these files often have accessibility issues or may be opened in a separate application causing confusion for users. Edit the link to add a notice such as (PDF) to the link text or title attribute. Ensure that the document complies with accessibility standards. ADA compliance for these documents must be verified using the native software such as Adobe Acrobat or MS Word.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['link_to_non_html_content']['Settings'] = __('Anchor tags pointing to non html content such as to a PDF or MS Word document.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['link_to_non_html_content']['Reference'] = __('WCAG 2.1 (Level A) - 2.4.4','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['link_to_non_html_content']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#link-purpose-in-context';
$wp_ada_compliance_basic_def['link_to_non_html_content']['HelpURL'] = 'https://www.w3.org/TR/WCAG20-TECHS/H30.html';
$wp_ada_compliance_basic_def['link_to_non_html_content']['HelpINSTR'] = __('Edit the link to add a notice such as (PDF) to the link text or title attribute. CSS may be used to show the notice when the link is active or a small icon may be used inside the anchor tag with the alternate text set to "PDF".','wp-ada-compliance-basic');

/********************************/
// elementor table of contents
/********************************/
$wp_ada_compliance_basic_def['elementor_toc']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! An Elementor Table of Contents widget was found. The Elementor Table of Contents widget does not move focus to the selected anchor when activated which disorients screen reader users. Edit the content to use a different navigation mechanism or upgrade to the full version to automatically correct this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['elementor_toc']['StoredError'] = __('An Elementor Table of Contents widget was found. The Elementor Table of Contents widget does not move focus to the selected anchor when activated which disorients screen reader users. Edit the content to use a different navigation mechanism or upgrade to the full version to automatically correct this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['elementor_toc']['Settings'] = __('Use of the Elementor Table of Contents widget.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['elementor_toc']['Reference'] = __('WCAG 2.1 (Level A) - 2.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['elementor_toc']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#keyboard';
$wp_ada_compliance_basic_def['elementor_toc']['HelpURL'] = 'https://www.w3.org/WAI/WCAG21/Understanding/keyboard-no-exception.html';
$wp_ada_compliance_basic_def['elementor_toc']['HelpINSTR'] = __('Edit the content to use a different navigation mechanism or upgrade to the full version to automatically correct this issue.','wp-ada-compliance-basic');


/********************************/
// elementor toggle or accordion
/********************************/
$wp_ada_compliance_basic_def['elementor_toggles']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! An Elementor Tab, Toggle or Accordion widget was found. Elementor Tab, Toggle and Accordion widgets may cause confusion for screen reader users. The Elementor Tab widget does not support use of the space key. Elementor Toggle and Accordion widgets provide no audible indication of the toggle state (open or closed) when operated by screen reader users. Edit the content to use a different formating option or upgrade to the full version to automatically correct this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['elementor_toggles']['StoredError'] = __('An Elementor Tab, Toggle or Accordion widget was found. Elementor Tab, Toggle and Accordion widgets may cause confusion for screen reader users. The Elementor Tab widget does not support use of the space key. Elementor Toggle and Accordion widgets provide no audible indication of the toggle state (open or closed) when operated by screen reader users. Edit the content to use a different formating option or upgrade to the full version to automatically correct this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['elementor_toggles']['Settings'] = __('Use of Elementor Tab, Toggle or Accordion widgets.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['elementor_toggles']['Reference'] = __('WCAG 2.1 (Level A) - 2.1.1','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['elementor_toggles']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#keyboard';
$wp_ada_compliance_basic_def['elementor_toggles']['HelpURL'] = 'https://www.w3.org/WAI/WCAG21/Understanding/keyboard-no-exception.html';
$wp_ada_compliance_basic_def['elementor_toggles']['HelpINSTR'] = __('Edit the content to use a different formating option or upgrade to the full version to automatically correct this issue.','wp-ada-compliance-basic');

/********************************/
//elementor carousel widget autoplay
/********************************/
$wp_ada_compliance_basic_def['elementor_carousel_autoplay']['DisplayError'] = __('<i class="fas fa-ban" aria-hidden="true"></i> WARNING! One or more Elementor image carousel widgets were found to be set to autoplay. Any moving, blinking or scrolling information that starts automatically, lasts more than five seconds, and is presented in parallel with other content must have a mechanism for the user to pause, stop, or hide it. Edit the carousel settings to disable autoplay or enable the autocorrect feature under the Content Filters tab.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['elementor_carousel_autoplay']['StoredError'] = __('An Elementor image carousel widget was found to be set to autoplay. Any moving, blinking or scrolling information that starts automatically, lasts more than five seconds, and is presented in parallel with other content must have a mechanism for the user to pause, stop, or hide it. Edit the carousel settings to disable autoplay or enable the autocorrect feature under the Content Filters tab.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['elementor_carousel_autoplay']['Settings'] = __('Elementor image carousel widget set to autoplay.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['elementor_carousel_autoplay']['Reference'] = __('WCAG 2.1 (Level A) - 2.2.2','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['elementor_carousel_autoplay']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG21/quickref/#pause-stop-hide';
$wp_ada_compliance_basic_def['elementor_carousel_autoplay']['HelpURL'] = 'https://www.w3.org/WAI/tutorials/carousels/';
$wp_ada_compliance_basic_def['elementor_carousel_autoplay']['HelpINSTR'] = __('Edit the carousel settings to disable autoplay or enable the autocorrect feature under the Content Filters tab.','wp-ada-compliance-basic');

/********************************/
// about web accessibility and help
/********************************/
$wp_ada_compliance_basic_def['accessibility_help']['DisplayError'] = __('<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ALERT! This page does not appear to include an option to allow users to seek help or report accessibility problems on the website. A link should be provided to a page that describes your efforts to meet accessibility standards and that includes a link to an accessible form or phone number to report problems. Upgrade to the full version and enable the Web Accessibility Widget to quickly resolve this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['accessibility_help']['StoredError'] = __('This page does not appear to include an option to allow users to seek help or report accessibility problems on the website. A link should be provided to a page that describes your efforts to meet accessibility standards and that includes a link to an accessible form or phone number to report problems. Upgrade to the full version and enable the Web Accessibility Widget to quickly resolve this issue.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['accessibility_help']['Settings'] = __('Non availablity of information to allow users to seek help or report accessibility problems on the website.','wp-ada-compliance-basic');

$wp_ada_compliance_basic_def['accessibility_help']['Reference'] = __('WCAG 2.1 (Level AAA) - 3.3.5','wp-ada-compliance-basic');
$wp_ada_compliance_basic_def['accessibility_help']['ReferenceURL'] = 'https://www.w3.org/WAI/WCAG22/quickref/#help';
$wp_ada_compliance_basic_def['accessibility_help']['HelpURL'] = 'https://www.w3.org/WAI/WCAG21/Techniques/general/G71.html';
$wp_ada_compliance_basic_def['accessibility_help']['HelpINSTR'] = __('Upgrade to the full version and enable the Web Accessibility Widget to quickly resolve this issue or provide a link to page that describes efforts to meet accessibility standards and that provides a link to an accessible form or phone number to report problems. ','wp-ada-compliance-basic');
?>