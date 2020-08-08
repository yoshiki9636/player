import plistlib as plist
import csv

with open("lib.xml", 'rb') as fp:
    pl = plist.load(fp)

strip = pl['Tracks']
tracks = []
delete_list = ['Sort Name', 'Sort Album', 'BPM', 'Sort Artist',
               'Sort Composer', 'Album Artist', 'Protected', 'Artwork Count',
               'Purchased', 'Release Date', 'Composer', 'Kind',
               'Disc Number', 'Disc Count', 'Date Modified', 'Date Added',
               'Sample Rate', 'Track Type', 'Location', 'File Folder Count',
               'Library Folder Count']

for track in strip:
    if strip[track]['Kind'] == 'MPEG-4ビデオファイル': continue
    # delete unused tag
    for tag in delete_list:
        try:
            del strip[track][tag]
        except:
            pass
    # add Compilation false value
    strip[track].setdefault('Compilation',False) 
    tracks.append(strip[track])

csv_header = []
for item in tracks[0]:
    print(item.lower().replace(' ','_') + ' integer,')
    csv_header.append(item)

print(csv_header)

with open("track_data.csv", "w" ) as f:
    writer = csv.DictWriter(f,csv_header,lineterminator="\n")
    writer.writerows(tracks)

