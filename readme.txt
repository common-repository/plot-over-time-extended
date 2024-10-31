=== Plot Over Time - Extended ===
Contributors: Rodger Cravens
Plugin Homepage: http://www.save-o-matic.com
Author Homepage: http://www.save-o-matic.com
Example of Plugin: http://www.ourroadtohealth.com/index.php/data-collection/total-blood-pressure/
Tags: graph,chart,custom fields,google chart API,weight tracking,diet,charts,graphs
Requires at least: 3.0
Tested up to: 3.9
Stable tag: 1.4.0

== Description ==
I have used Plot Over Time for a long time when found that I needed to put multiple charts on one page and category restrictions. The plugin did not allow it. I am insistent on not needing a new plugin - as this one has worked just fine for me… So, I made changes.

My first change is small in user effort, but large in execution... I added an optional parameter for chart_num. This optional parameter is not needed if you are using the plugin for a single chart page. It is only needed to identify what chart you are making on a multi-chart page...

<strong>Single chart on a page...</strong>

	[plot_ext field1="Heart Rate"]
	
<strong>Multi-Chart page...</strong>

	[plot_ext field1="Heart Rate" chart_num="1"]
	[plot_ext field1="Blood Pressure" chart_num="2"]

Want to see it in action?  http://www.ourroadtohealth.com/index.php/data-collection/total-blood-pressure/

My second change... I added an optional parameter for post category restriction. This optional parameter is not needed if you are pulling from all post categories. It is only needed to identify what 1 post category you want to limit the data to...

<strong>Single chart on a page with category selection...</strong>

	[plot_ext field1="Heart Rate" post_cat="8"]

<strong>Let's Get Started: </strong>

<strong>1. </strong>To get it up and running: first, you'll need to put data in your posts.  So, in a post, go to the section under your post called "Custom Fields". Create a field you want to track (for instance, "Heart Rate") and give it a value.

<strong>2. </strong>You'll need at lest two posts with data in them before the plugin can do it's thing - so add data to another post.

<strong>3. </strong>Now that you have at least two posts with data, you can create a post that creates a graph.  For this example, you could simply use:

	[plot_ext field1="Heart Rate"]

This will go through all posts that have a custom field called "Heart Rate" and plot them on a nice Google Chart Tools LineChart. Plot Over Time - Extended supports up to 10 fields per graph.  If you wanted to work with more points of data (again, up to 10), simply add more Custom Fields:

	[plot_ext field1="Heart Rate" field2="Weight" field3="Workout Time"]

The legend is automatically added, and each datapoint is able to be clicked to get a callout about it's datapoint. Or you can move the legend around with legend="left" or legend="right". If you want to get rid of the legend:

	[plot_ext field1="Heart Rate" legend="none"]

<strong>Types: </strong>Valid types chart types for Plot Over Time are:  AreaChart, LineChart, PieChart (not particularly useful in this implementation), BarChart, and ColumnChart. To use other types of graphs:

	[plot_ext field1="Heart Rate" type="BarChart"]

<strong>Height / Width: </strong>You can set your own width and height for the chart with Width and Height (default: 400 X 300). Don't change the height or width using the options="height: ", instead use height="300" or similar.  Changing it in the options that are passed to the graph won't change the size of the div it exists in.

	[plot_ext field1="Heart Rate" width="500" height="300"] 

<strong>Data Limits: </strong>By default, Plot Over Time uses ALL data from all posts and pops it onto a graph.  You can limit it with two options:

<strong>1. </strong>You can determine the maximum number of days worth of data (starting from now and going backward) with maxdays. This would show a one month span of data:

	[plot_ext field1="Heart Rate" maxdays="30"]

<strong>2. </strong>You can attach a graph to that post's published date with usepostdate:

	[plot_ext field1="Heart Rate" usepostdate="true"]

So if the post was 3 weeks old, it would only show data from it's publication date of three weeks ago and older - <strong>this allows you to see changes post by post</strong>!

<strong>Dates: </strong>The date format defaults to m/d/y - IE, 12/31/11.  You can change it with dateformat. This would instead put 2011-12-31 on the graph:

	[plot_ext field1="Heart Rate" dateformat="Y-m-d"]

<strong>Titles: </strong>This would add a title above your graph that reads "My Graph!".  

	[plot_ext field1="Heart Rate" options="'title': 'My Graph!'" chart_num="1"]

<strong>Wrapping Up: </strong>Don't like the colors of the graph, or want to add a few new options?  No problem - any Google Visualization Tools options is available using the option parameters.

<strong>More Info: </strong>For more information on the options available visit the Google Chart Tools homepage: http://code.google.com/apis/chart/index.html

<strong>Rules: </strong>

<strong>1. </strong>If you define 5 fields you want to read from, and a post only had four fields?  It's going to ignore you.

<strong>2. </strong>It doesn't do data interpolation.  If there's a gap in your data, it simply ignores it - it doesn't give it a "0", it just plain doesn't plot it.  (That's how he wanted it.  Other opinions may differ - mine does not.)

<strong>3. </strong>f you tell it you want to see 30 days of data, and you've only got three days of data in your posts?  It's only going to show a graph that spans three days.

<strong>All data integrity is up to you.</strong>

<strong>If you download, please rate the plugin. This is one of the few feedback methods available. If you have a low opinion, please allow me to try to fix it first before leaving a bad review.</strong>

== Installation ==

1. Download the Plot Over Time plugin and you will get a directory called "plot_ext" when you unzip the downloaded file. Upload the "plot_ext" directory to your WordPress plugin's directory (e.g. `/wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' menu in the Wordpress dashboard.

== Frequently Asked Questions ==

= Is it possible to go beyond the 10 field limit? =

Yes, but it would take code changes. I could do it, but I did not see a need at this time. If you have a need, let me know and I might be able to slip it into the next release.

= Can you add this / that / the other? =

Yes, but I have no time lines that I would do it in. I might finish tomorrow, I might finish next month. I gladly take requests as just that, requests... I am not asking for money, so please do not have expectations beyond a free service...

= Did you write this plugin from scratch? =

The greatest form of flattery is plagiarism :-) I am not the original author of the base plugin - I highly modified an existing one. This is a modified and re-branded version of the Plot Over Time plugin by MidnightRyder: http://www.midnightryder.org

== Changelog ==

Version 1.0, July 30, 2014  - Initial Release
Version 1.1, July 30, 2014  - Issues with dates solved
Version 1.2, July 30, 2014  - Minor issue with width settings
Version 1.3, July 30, 2014  - Reduced SQL time, fixed data sorting and limit issues from original plug-in
Version 1.4, July 30, 2014  - Fixed post_cat and maxdays issue created by SQL cleanup in previous version

== Upgrade Notice ==

= 1.4 =
Fixed post_cat and maxdays issue created by SQL cleanup in previous version

== Screenshots ==
1. Example of AreaGraph formatted data
2. Example of LineChart formatted data
3. Example of the PieChart formatted data
