from json import *
from ddbfuncs import dumpICAOranges
rg=dumpICAOranges()
#print (rg)
j = dumps(rg)
print (j)
