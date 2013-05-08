These are some things that happened during the submit phase or have to be noted so solutions can be correctly checked.


###Challenge 4
* Due to being unable to seek in a +2GB file in PHP, running on Windows, I've split the integers file on 4 parts. Instructions on how to do that is specified on the solution.

### Challenge 6
* The submit input was incomplete, check the input/ folder.

### Challenge 10
* During the submit phase, one of the temporal files was deleted, causing PHP to crash.
* There are 2 solutions. 10.php, the file used in the submit phase and 10_2.php, the same as the submit phase but with buffered MD5 (slower in PHP for unknown reason).

### Challenge 17
* Optimizations were included to calculate the sum of the digits faster than calculating the entire factorial. It would be faster if GMP was used instead of BCMath.

### Challenge 18
* Optimizations were made directly into machine instructions. That decreased the test case time from 25 seconds to 2.

### Skipped challenges
* The last used source code is included. Not reliable.

### PHP Extensions
* The pthreads extension wasn't used in the contest. Instead, the GMP, cURL, Hash and GD2 extensions had to be used.
 