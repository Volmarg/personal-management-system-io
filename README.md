<p align="center">
<img src="https://github.com/Volmarg/personal-management-system/blob/main/public/logo-small.png?raw=true" width="80px;" />
</p>
<h1 align="center">Personal Management System - I/O</h1>
<p align="center"><i>Insert and Output tool for providing personal data over the internet</i></p>

<h3>Description</h3>
<hr>
<p align="justify">
	This project is used as proxy for receiving and displaying the data transferred from <b><a href="https://github.com/Volmarg/personal-management-system">Personal Management System</a></b>.
    This tool is <b>Input</b> and <b>Output</b> only - it does not and never will have any type of interface for that.
</p>

<h3>Reasoning/Purpose</h3>
<hr>

<p align="justify">
    The <b><a href="https://github.com/Volmarg/personal-management-system">Personal Management System</a></b> was designed to never, ever be available online. The very purpose
of the project is to keep private data hidden safely locally. However... things have changed and I will need some of the data also accessible over the internet on my phone, laptop,
desktop - wherever it's possible.
</p>

<p align="justify">
    Naturally passing the data over the internet is... risky - can be intercepted, leak from the server etc. to prevent such case this project is additionally secured:
    <ul>
        <li> data transferred from <b><a href="https://github.com/Volmarg/personal-management-system">Personal Management System</a></b> is already encrypted,</li>
        <li> information in GUI are only accessible after logging in,</li>
        <li> login form additionally requires providing valid encryption key,</li>
        <li> data is constantly removed from DB via cron and when user logs out,</li>
        <li> <b>PMS</b> data is always rejected unless the <b>PMS-IO</b> `transferAllowed` flag is set to true,</li>
        <li> encryption key used for logging in is stored only in session,</li>
        <li> country restrictions access - using external API to check the country IP,</li>
    </ul>
</p>

<h2>Preview</h2>
<hr>

<img src="https://github.com/Volmarg/notifier-proxy-logger/blob/main/github/1.jpg?raw=true">

<hr>	

<img src="https://github.com/Volmarg/notifier-proxy-logger/blob/main/github/2.jpg?raw=true">

<hr>	

<img src="https://github.com/Volmarg/notifier-proxy-logger/blob/main/github/3.jpg?raw=true">

<h2>Tech</h2>
<hr>
<p style="text-align:justify;">
   <b>Personal Management System - I/O</b> is a web application which can be ran either in Windows and Linux environment. 
Everything is by default tested on Ubuntu 20.x.
</p>

<h3>Used languages/frameworks/solutions</h3>

<ul>
<li>Php 8.0</li>
<li>JS</li>
<li>VueJS 3</li>
<li>TypeScript</li>
<li>Symfony 5.x</li>
<li>MySQL</li>
<li>Css</li>
<li>Scss</li>
<li>Node 10.22.1</li>
<li>Bootstrap 5</li>
<li>Webpack</li>
</ul>