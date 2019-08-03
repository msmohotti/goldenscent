# GoldenScent Tech Test

This source code is for partner referral functionality. When a customer come referred by a partner
the order invoice and shipments are split into two.

## Design patters used 
#### Factory method
This pattern is used when instantiating classes and create objects.


`e.g. getModel('sales/order') or helper(‘goldenscent_partner')`

As a parameter we transfer an abstract name of a class which instance we want to create.


#### Singleton
Specifies that at any given time there can be only one instance of a given class, and allows global access to it.

In this source code we have used singleton pattern ot instantiate observers.

## Future Enhancements
We can create an admin grid to show all the partners. From this we will be able manage all the partners from admin.
Also by doing this we can put validation. So only available partners will be able to refer traffic to the website.