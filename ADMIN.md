# Admin panel documentation

This package is an extension of the [Template package](https://github.com/EscolaLMS/Templates/blob/main/ADMIN.md) and is used for managing email templates.

Templates are defined in the *Templates* and *Email* tabs. From the template list, you can create, edit or delete a template.

[img]

Each email template has a name, an event, and you can set the template to be the default.
Sending an email is only possible for the default template.
The list of events available for selection may vary depending on the installed *escolalms* packages. Each package emits its own events.

[img]

Clicking the "Preview" button will send an email to you. The values of the variables will be mocked.

[img]

When defining a template, you have variables to use. There are two types of variables global and event-defined. Global variables store general, system-related information, personalized variables store information directly related to the event.
Variables use a convention, with the @ sign before the variable name, to use a variable in a template you need to put the @ sign and the variable name, e.g. @VarSimpleName. 

[img]

The variables that are in the *required variables* section are the ones you must use in your template.

[img]

Mjml is used to create the content of an email.

[img]

You can use *Settings* to save recurring parts of the template, e.g. footer or header.  
Just define new variables in the *mail* group with the mjml code and then use those variables in the template.

[img]

[img]
