With this ansible scipt you could create a supernode für "Freifunk Königswinter" from scratch.

You just need to install a simple ubuntu 20 lts virtual or physical machine and ansible via apt.

After installation just login with your normal user and clone the whole repo in your home directory.

**You must change the parameters in the playbook.yml of course!**
The parameters should be explained or self explaining.


Then you could start the installation with a command like `ansible-playbook  playbook.yml -b --ask-become-pass`
