import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { NO_ERRORS_SCHEMA } from '@angular/core';

import { SalesOrdersTableComponent } from './sales-orders-table.component';

describe('SalesOrdersTableComponent', () => {
    let component: SalesOrdersTableComponent;
    let fixture: ComponentFixture<SalesOrdersTableComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [SalesOrdersTableComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(SalesOrdersTableComponent);
        component = fixture.componentInstance;
    });

    it('should render `spy-table` component', () => {
        const tableComponent = fixture.debugElement.query(By.css('spy-table'));

        expect(tableComponent).toBeTruthy();
    });

    it('should bind @Input(config) to `config` of `spy-table` component', () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        } as any;
        const tableComponent = fixture.debugElement.query(By.css('spy-table'));

        component.config = mockTableConfig;
        fixture.detectChanges();

        expect(tableComponent.properties.config).toEqual(mockTableConfig);
    });
});
