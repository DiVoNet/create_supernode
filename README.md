You could find an ansible script in this repo to install a supernode for the freifunk hood BonnV2 from scratch.

You just need to install a simple debian virtual or physical maschine and ansible. **Please install ansible via pip, because the version, which is shipped with debian stable, is way to old!**

After installation just login with your normal user and clone the whole repo in your home directory.

**You must change the parameters in the playbook.yml of course!**
The parameters should be explained or self explaining.


Then you could start the installation with a command like `ansible-playbook  playbook.yml -b --ask-become-pass`
