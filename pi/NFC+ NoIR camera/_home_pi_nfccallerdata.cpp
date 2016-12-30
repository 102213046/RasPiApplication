#include <stdio.h>
#include <string.h>

int main(){
	FILE *fPtr; //read
	FILE *fp; //write
	char data[1024];
	char ReadFromNfc[1024]; //把從nfc讀到的值存到test1存到test1
	fPtr=fopen("/cooking/arduiPi/orignfctag.txt","r");
	fp = fopen("nfccaller.txt","w");
	if ( fPtr ==NULL ) { printf("開讀檔失敗!"); } /* 處裡開讀檔失敗的情形 */
	for(int a=0;a<10;a++){
		fscanf(fPtr , " %s " , &data[a]); /* 讀入100個字元到 data[100] */
		
		if(a==1){
			fprintf(fp," %s ", &data[a]);
		}
	}
	fclose(fPtr);
	fclose(fp);
}
