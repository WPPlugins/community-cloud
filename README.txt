=== Community Cloud Plugin ===
Contributors: Miguel dos Santos
Website: http://www.webaddict.co.za/2007/09/04/community-cloud-wordpress-plugin
Tags: comments, community, cloud, widget, sidebar, page
Requires at least: 2.0
Tested up to: 2.3
Stable tag: 1.2.3


== Description ==

This plugin searches your wordpress database and displays a 'tag cloud' of all the people in your community who have contributed to our blog by commenting. 
The more comments someone has made on your blog the bigger and bolder their tag. Thus, it's a tag cloud of community participants for your blog. Not meant to replace your regular blogroll, only meant to facilitate it.
You also have options to customize the community cloud to your blog and needs.
People can see how big your community is in a more visually impactful way, as well as being able to see who the major contributors are compared to the people who just comment once off.


== Installation ==

1. Download and Extract the Community Cloud plugin
2. Upload it via FTP to your wordpress plugins directory (/wp-content/plugins/)
3. Activate the Community Cloud in the Plugins section of your blog’s admin.
4. In the Options -> Community Cloud section change the default settings to fit your blog.
5. Once installed, to display the community cloud on your blog you can either
	a. Create a new Page on your blog and in the Page Content area, enter the following tag where you want the community cloud to appear.
     	<!--community cloud--> and publish.
	b. Or, there is also sidebar widget, so you can just go to the Widgets tab within the Presentations menu and drag-n-drop the Community Cloud widget into your sidebar.


== Frequently Asked Questions ==

The main problem that people have been reporting is that the plugin doesn't show up in their plugin list once they've uploaded it:
- The solution to this problem is to make sure to check that the file permissions on the plugin is set correctly. You need "CHMOD" the files to 755, or 777 just to be safe (That's if you're hosted on a Linux server, If you're hosted on the Windows server you shouldn't experience this problem)


== Change Log ==

1.2.3:
======
* Improved comment count display, to allow comment counts to appear in tooltip text, in the link, or not at all
* removed old url validate function and replaced it with something more simple
* fixed font colors, so if you leave out max. font color and min. font color it falls back to your theme's default link colors rather than reverting to black
1.2.2:
======
* Added the option to specify your own link seperator character
* Option to display comment authors that haven't entered website URLs as well as those that have
* Show number of comments author has posted in link tooltip text
1.2.1: 
======
* Allow plugin files to work in any subdirectory. It used to be that the plugin only functioned properly in the plugins/community_cloud/ directory
1.2: 
====
* added sidebar widget
* added option link back to the author of the plugin
* added the option to set your comment threshold (default 1)
* added option to order your cloud by most recent commenter to earliest, top commenter to lowest, and random (default)
1.1: 
====
* added mysql_escape_string for security
1.0: 
====
* initial release


Have Fun.

Miguel dos Santos
Your Group of Web AddiCT(s);
http://www.webaddict.co.za
