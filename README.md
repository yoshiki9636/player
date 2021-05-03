# player

Google Home's tiny music player using php and postgresql 

Getting Started

 Software Requirement
   1. Linux 
   2. PHP/apache
   3. Postgresql
   4. Nodejs
   5. Google Home notifier ( for Nodejs )

 This player uses Postgresql for managing artist/album/track names, so you need to import
these data from itunes's xml data(plist formatted) to Postgresql. Sound files in ituens also
need to be placed where are accessable from Linux machine.

Instruction :
  1. Copy expected itunes sound data (under itunes\Music\) to where are accessable
     from Linux machine. Directry/file names and structures are need to keep as originals.
     These are used to find exact the sound files.
  2. Get ituens's library.xml file (Japanese: ライブラリ.xml).
     Use plist2csv.py to make a csv file which is for Postgresql importing.
  3. Import the csv file to Postgresql. The table name is track_table.
  4. Copy all repository directory file to /var/www/html/ (for Ubuntu).
     The directory path is depend on OS distribution.
  5. Make synbolic link from the copied itunes directory at /var/www/html/ .
  6. Make player/tmp directory and change the directory's access mode to readable/writable
     from anyone.
        chmod 777 tmp
  7. Setting checkdb.php setting valus DB user name, passwd, etc.
  8. Access http://<machine address>/player/ from web browser to use player.

  For detail instraction please see Qiita page.

Author
  Yoshiki Kurokawa  @yoshiki9636

 * @auther		Yoshiki Kurokawa <yoshiki.k963@gmail.com>
 * @copylight	2020 Yoshiki Kurokawa
 * @license		https://opensource.org/licenses/MIT     MIT license
 * @version		0.1
