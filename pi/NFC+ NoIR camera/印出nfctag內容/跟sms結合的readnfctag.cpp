#include <stdio.h>
#include <string.h>

int main(){
	FILE *fPtr; //read
	char data[1024];
	fPtr=fopen("orignfctag.txt","r");
	if ( fPtr ==NULL ) { printf("�}Ū�ɥ���!"); } /* �B�̶}Ū�ɥ��Ѫ����� */
	for(int a=0;a<7;a++){
		fscanf(fPtr , " %s " , &data[a]); /* Ū�J100�Ӧr���� data[100] */
		if(a==1)
			printf( "caller: %s \n", &data[a] );	
		if(a==2)
			printf( "bloodtype: %s \n", &data[a] );
		if(a==3)
			printf( "ID: %s \n", &data[a] );	
		if(a==4)
			printf( "Plate: %s \n", &data[a] );
		if(a==5)
			printf( "Emergency Contact: %s \n", &data[a] );	
		if(a==6)
			printf( "Contact num: %s \n", &data[a] );
	}
	fclose(fPtr);
}


