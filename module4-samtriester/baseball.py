import re
import argparse
import sys
import os

# parser = argparse.ArgumentParser()
# parser.add_argument("-f", type=argparse.FileType())

# args = parser.parse_args()
def __main__():
    lines = []
    people=[]
    at_bats =[]
    hits =[]
    if(len(sys.argv)<2):
        print("Error: Please submit a .txt file following the script title")
    elif(len(sys.argv)>2):
        print("Error: Please only submit one argument (a .txt file) following the script title")
    elif(not os.path.exists(sys.argv[1])):
        print(f"Error: file {sys.argv[1]} does not exist")
    else:
        is_match =re.search('\w*.txt',sys.argv[1])
        if is_match:
            with open(sys.argv[1]) as f:
                lines = f.readlines()
                lineNum = 0
            
            for line in lines:
                lineNum += 1
                is_match =re.search('^[^=][^\n][\w\s]*',line)
                if is_match:
                    name= re.split('\sbatted', line)
                    if name[0] in people:
                        i=people.index(name[0])
                        game= re.findall('\d',name[1])
                        at_bats[i] +=int(game[0])
                        hits[i]+=int(game[1])
                    else:
                        people.append(name[0])
                        game= re.findall('\d',name[1])
                        at_bats.append(int(game[0]))
                        hits.append(int(game[1]))
            batting_average=[round(i / j,3) for i, j in zip(hits, at_bats)]
            tup =list(zip(people,batting_average))
            sorted_players= sorted(tup, key=lambda x: x[1], reverse=True)           
            for player in sorted_players:
                average = format(player[1], ".3f")
                print(f"{player[0]}: {average}")
        else:
            print("Error: file must end in .txt extension")
    return 0
__main__()